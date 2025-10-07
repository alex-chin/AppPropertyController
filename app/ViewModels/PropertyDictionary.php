<?php

namespace App\ViewModels;

use App\Models\Dictionary;
use App\Models\Item;
use App\Models\ItemValue;

/*
 * Абстрактный класс для создание компонента справочника
 * - загрузка вариантов выбора
 * - генерация html form элемента
 * - генерация правил проверки ввода элементов
 * -
 *
 * set('integer', 'float', 'integer_range', 'float_range',
 *     'list', 'check_list', 'logical',
 *     'binary', 'text', 'string', 'url_video', 'url_file')
 */

/**
 *
 */
abstract class PropertyDictionary
{
    const PREFIX = 'prop_';
    public $dictionary;
    public $is_required = false;
    protected $variants = array();
    protected $key;
    protected $view;
    protected $rules = array();
    // поле из БД
    public $value;

    public function __construct(Dictionary $dictionary, $view)
    {
        // таблица словаря
        $this->dictionary = $dictionary;
//        $this->key = self::PREFIX . $dictionary->slug;
        // ключ доступа к компонентам
        $this->key = $dictionary->slug;
        $this->view = $view;
        // для списков создание вариантов выбора
        $this->variants = $this->load_variants();
        // Для необязательных должны быть возможность null
        $this->rules=['nullable'];
        // собираем правила валидации и приводим к laravel
        $this->collectRules();

    }

    /**
     *  Генерация html form элемента
     *
     * @return string
     */
    public function render()
    {
        $units =  empty($this->dictionary->unit) ? '': " ({$this->dictionary->unit}) ";

        return view($this->view,
            [
                'key' => $this->prop_key(), // ключ элемента формы
                'prop' => $this->dictionary,    // доступ к таблице словаря
                'variants' => $this->variants,   // варианты выбора
                'value' => $this->field_key(),
                'units' => $units
            ])
            ->render();
    }

    public function get_variants()
    {
        return $this->variants;
    }

    public function get_key()
    {
        return $this->key;
    }

    protected function load_variants()
    {
        return array();
    }

    public function hasRules()
    {
        return !empty($this->rules);
    }

    public function getRules()
    {
        return $this->rules;
    }

    protected function collectRules()
    {
        if ($this->dictionary->is_required) {
            $this->rules[] = 'required';
            $this->is_required = true;
        }
    }

    /**
     * Сохранение кат-зав параметров в модели ItemValue
     *
     * @param Item $item - формирование атрибутов для этого объявления
     * @param $parameter - параметр переданный в post запросе
     * @return void
     */
    public function saveForItem(Item $item, $parameter)
    {
        /* @var $value ItemValue */

        // собираем все существующие параметры для item, если не находим - создаем
        // идентификация по коду
        $value = $item->values()->firstOrNew(['slug' => $this->dictionary->slug]);

        // формирование связи с Item
        $value->item()->associate($item);
        // формирование связи со словарем
        $value->dictionary()->associate($this->dictionary);
        // дублирование типа параметра
        $value['type'] = $this->dictionary->type;
        // непосредственная запись атрибута зависит от класса атрибута
        $this->saveAttribute($value, $parameter);
        $value->save();
    }

    /**
     * Сохранить параметр
     *
     * @param ItemValue $value - параметр хранения
     * @param $parameter - абстрактный параметр
     * @return mixed
     */
    abstract protected function saveAttribute(ItemValue $value, $parameter);


    /**
     *
     * Текстовое поле для простого просмотра,
     * используется в get_label_field
     *
     * @return string
     */
    public function field()
    {
        return empty($this->value) ? '' : $this->value->text;
    }

    /**
     *
     * Код поля
     *
     * @return string
     */
    public function field_key()
    {
        return $this->field();
    }

    /**
     * Простой показ названия поля и значения
     *
     *  ['label' => $this->dictionary->name, 'value' => ...];
     *
     * @return mixed
     */
    public function get_label_field()
    {
        $field = ['label' => $this->dictionary->name, 'value' => ''];
        if (!empty($this->value)) {
            $field['value'] = $this->field();
        }
        return $field;
    }

    /**
     *
     * генерация ключей для идентификации полей Forms
     *
     * @return string
     */
    public function prop_key()
    {
        return self::PREFIX . $this->key;
    }


}
