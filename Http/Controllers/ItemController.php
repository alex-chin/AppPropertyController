<?php

namespace App\Http\Controllers;

use App\Facades\SEOFacade;
use App\Facades\StatFacade;
use App\Facades\StatItemFacade;
use App\Jobs\ProcessItemStatistic;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ChatQuickMessage;
use App\Models\Color;
use App\Models\Country;
use App\Models\GeoPosition;
use App\Models\Item;
use App\Models\StatItem;
use App\Models\User;
use App\Services\SearcherHelper;
use App\StateMachines\ItemStateMachine;
use App\ViewModels\PropertyController;
use Asantibanez\LaravelEloquentStateMachines\Exceptions\TransitionNotAllowedException;
use Butschster\Head\Facades\Meta;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ItemController extends Controller
{
    /**
     * @param string $slug
     * @param int $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|RedirectResponse
     */
    public function show(string $slug, int $id)
    {
        $item = Item::findOrFail($id);

        // Запрещаем просматривать чужое неактивное объявление всем, кроме пользователям с разрешением редактирвоать объявления (менеджер и админ)
        if (
            (
                (
                    !Auth::guest()
                    && auth()->user()->id !== $item->user->id
                    && !auth()->user()->hasPermission('edit_items')
                )
                || Auth::guest()
            )
            && $item->state()->state !== ItemStateMachine::ACTIVE
        ) {
            toastr()->error('Объявление не в активном состоянии', 'Ошибка');
            return redirect(route('home'));
        }

        $category = $item->findCategory();
        $prop = new PropertyController($category);
        $prop->load_values($item);

        // быстрые сообщения доступны только для покупателя (для своих объявлений недоступны)
        if (
            !Auth::guest()
            && auth()->user()->id === $item->user->id
        ) {
            $chatQuickMessages = collect([]);
        } else {
            $chatQuickMessages = ChatQuickMessage::where('is_active', 1)->get();
        }

        $seo = SEOFacade::generate_by_item($item);

        Meta::setTitle($seo['title']);
        Meta::setDescription($seo['description']);

        StatFacade::set_item($item, $category);

        // Поставить задачу статистики в очередь
        ProcessItemStatistic::dispatch($item, User::get_uniq_user());

        return view('item.show', [
            'item' => $item,
            'fields' => $prop->fields(),
            'labels' => $item->getAttributeLabels(),
            'chatQuickMessages' => $chatQuickMessages,
        ]);
    }

    /**
     * Форма создания объявления
     *
     * @param Request $request
     * @param int $categoryId
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function make(Request $request, int $categoryId)
    {
        $item = Auth::user()->getTempItem();
        if ($item) {
            $category = $item->category;
            // Если сменили категорию, то удаляем черновик
            if ($category->id !== $categoryId) {
                $item->forceDelete();
                $item = null;
            }
        }

        if (!$item) {
            $category = Category::findOrFail($categoryId);
            $item = Item::create([
                'is_public' => 0,
                'name' => $category->name, // т.к. поле в БД обязательное, то мы вынуждены его заполнить при сохранении черновика
                'category_id' => $categoryId,
                'user_id' => Auth::id(),
                'state' => ItemStateMachine::NEW,
            ]);
            $item->save();
            $item->name = ''; // Очищаем имя товара
        }

        $properties_render = new PropertyController($category);
        $brands = Brand::all()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name']];
        })->toArray();
        $countries = Country::all()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name']];
        })->toArray();
        $colors = Color::all()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name']];
        })->toArray();
        $colorsOptions = Color::all()->mapWithKeys(function ($item) {
            return [$item['id'] => ['class' => 'hex-' . substr($item['hex'], 1, strlen($item['hex']))]];
        })->toArray();

        StatFacade::set_category($category);

        return view('item.new', [
            'category' => $category,
            'item' => $item,
            'geo_position' => new GeoPosition,
            'properties' => $properties_render->render(),
            'brands' => $brands,
            'countries' => $countries,
            'colors' => $colors,
            'colorsOptions' => $colorsOptions,
        ]);
    }



    /**
     * Форма редактирования объявления
     *
     * @param Request $request
     * @param int $id
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function edit(Request $request, int $id)
    {
        $item = auth()->user()->items()->find($id);
        if (
            !$item
            || in_array($item->state, [ItemStateMachine::BANNED, ItemStateMachine::MODERATED])
        ) {
            /*return redirect()->back()->with([
                'message' => 'Вам недоступно данное действие',
                'alert-type' => 'error',
            ]);*/
//            return back()->withErrors('Данное действие недоступно.');
            toastr()->error('Данное действие недоступно.', 'Ошибка!');
            return back();
        }

        $category = $item->category()->first();

        $prop = new PropertyController($category);
        $prop->load_values($item);
        $geo_position = $item->geo_position ?? new GeoPosition;
        $brands = Brand::all()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name']];
        })->toArray();
        $countries = Country::all()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name']];
        })->toArray();
        $colors = Color::all()->mapWithKeys(function ($item) {
            return [$item['id'] => $item['name']];
        })->toArray();
        $colorsOptions = Color::all()->mapWithKeys(function ($item) {
            return [$item['id'] => ['class' => 'hex-' . substr($item['hex'], 1, strlen($item['hex']))]];
        })->toArray();

        StatFacade::set_item($item, $category);

        return view('item.edit', [
            'category' => $category,
            'item' => $item,
            'geo_position' => $geo_position,
            'properties' => $prop->render(),
            'brands' => $brands,
            'countries' => $countries,
            'colors' => $colors,
            'colorsOptions' => $colorsOptions,
        ]);
    }

    /**
     * Создать объявление по пост запросу
     *
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     *      в описании формы дб multipart/form-data
     *      https://ru.wikipedia.org/wiki/Multipart/form-data
     */
    public function store(Request $request, ?int $id = null)
    {
        return $this->save_post($request, $id, true);
    }

    public function save(Request $request, int $id)
    {
        return $this->save_post($request, $id);
    }

    public function save_post(Request $request, ?int $id = null, bool $create = false)
    {
        /**
         * Добавим в массив сохранения slug центрального города региона
         */
        if (!empty($request['fias_region'])) {
            $center_city_title = GeoPosition::getCenterCityTitleRegion($request['fias_region']);
            $request->request->add(['center_slug' => SearcherHelper::getCitySlugByCityTitle(trim($center_city_title))]);
        }

        // Категория обязательная для генерации катзав параметров
        $this->validate($request, ['category_id' => 'required']);

        // Инициализация Контроллера параметров
        $category = Category::find($request->input('category_id'));
        $properties_controller = new PropertyController($category);

        $toponyms_rules = ['fias_region' => 'required', 'fias_city' => 'required',];


        // генерация правил валидации
        $this->validate($request,
            array_merge([
                'name' => 'required',
                'price' => 'required',
                'description' => 'required',
                'set_delivery' => 'required',
            ],
//                $toponyms_rules, // Выделено для возможности локальной отладки без Dadata
                $properties_controller->rules()));

        if ($id) {
            $item = auth()->user()->items()->find($id);
            if (
                !$item
                || in_array($item->state, [ItemStateMachine::BANNED, ItemStateMachine::MODERATED])
            ) {
//                return back()->withErrors('Данное действие недоступно');
                toastr()->error('Данное действие недоступно.', 'Ошибка!');
                return back();
            }
            $item->fill($request->all());
            $geo_position = $item->geo_position ?? new GeoPosition;
            $item->geo_position()->save($geo_position->fill($request->all()));

        } else {
            $request['user_id'] = Auth::id();
            $item = Item::create($request->all());
            $item->geo_position()->create($request->all());
        }

        $item->save();

        // Переводим объявление в состояние "ожидает модерацию"
        // меняем статус через стейт-машину для логирования в истории смены состояний
        $item->state()->transitionTo(ItemStateMachine::MODERATE);

        // сохранение параметров
        $properties_controller->save($item, $request->all());

        toastr()->success('Объявление успешно ' . ($create ? 'создано' : 'отредактированно'), 'Операция успешно выполнена');

        return [
            'success' => true,
            'userId' => auth()->user()->id,
            'create' => $create,
        ];
    }

    /**
     * @param Request $request
     * @param int $id
     * @param string $state
     *
     * @return RedirectResponse|array
     */
    public function change_state(Request $request, int $id, string $state): RedirectResponse|array
    {
        $item = auth()->user()->items()->find($id);
        if (
            !$item
            || in_array($item->state, [ItemStateMachine::BANNED, ItemStateMachine::MODERATED])
        ) {
            if ($request->ajax()) {
                abort(403, 'Ошибка! Данное действие недоступно.');
            } else {
//                return back()->withErrors('Данное действие недоступно');
                toastr()->error('Данное действие недоступно.', 'Ошибка!');
                return back();
            }
        }

        try {
            $item->state()->transitionTo($state, [
                'comments' => $request['reason'] . ($request['reasonOther'] ? ' ' . $request['reasonOther'] : ''),
            ]);
            toastr()->success('Объявление переведено в новый статус', 'Операция успешно выполнена');
            if ($request->ajax()) {
                return ['success' => true];
            } else {
                return back();
            }
        } catch (TransitionNotAllowedException $exception) {
            if ($request->ajax()) {
                abort(403, 'Ошибка! Переход в данное состояние недоступно.');
            } else {
                toastr()->error('Переход в данное состояние недоступно.', 'Ошибка!');
                return back();
            }
        }
    }

    public function rank_now(Request $request, int $id)
    {
        $item = auth()->user()->items()->find($id);
        if (!$item || !in_array($item->state, [ItemStateMachine::ACTIVE])) {
            toastr()->error('Данное действие недоступно.', 'Ошибка!');
            return back();
        }
        $item->ranked_at = Carbon::now();
        $item->save();
        toastr()->success('Объявление поднято', 'Операция успешно выполнена');
        return redirect('/dashboard/active');
    }
}
