<?php

namespace App\ViewModels;

use App\Models\Category;
use App\Models\Dictionary;
use App\Models\Item;
use function React\Promise\Stream\first;


/**
 *  Управление группой справочников для Объявления
 *  - создание по описанию в Категории Объявления
 *  - генерация элементов формы
 *  - валидация данных
 *  - сохранение данных
 *
 */
class PropertyController
{
    protected $dictionaries;
    public $registery = array();

    /**
     * Создание структуры - описание в категории
     *
     * @param Category $category
     */
    public function __construct(Category $category)
    {
        /* @var $category Category */
        // todo order by order
        $this->dictionaries = $category->dictionaries()->get();
        $this->build_registery();
    }

    protected function build_registery()
    {
        /* @var $dictionary Dictionary */

        foreach ($this->dictionaries as $dictionary) {
            switch ($dictionary->type) {
                case 'list':
                    $property = new PropertyDictionaryList($dictionary, 'prop.list');
                    break;
                case 'binary':
                    $property = new PropertyDictionaryBinary($dictionary, 'prop.radio');
                    break;
                case 'integer':
                    $property = new PropertyDictionaryNumber($dictionary, 'prop.integer');
                    break;
                case 'float':
                    $property = new PropertyDictionaryNumber($dictionary, 'prop.float');
                    break;
                case 'integer_range':
                    $property = new PropertyDictionaryRange($dictionary, 'prop.integer_range');
                    break;
                case 'float_range':
                    $property = new PropertyDictionaryRange($dictionary, 'prop.float_range');
                    break;
                case 'text':
                    $property = new PropertyDictionarySimple($dictionary, 'prop.text');
                    break;
                case 'string':
                    $property = new PropertyDictionarySimple($dictionary, 'prop.string');
                    break;
                case 'logical':
                    $property = new PropertyDictionaryLogical($dictionary, 'prop.logical');
                    break;
                case 'check_list':
                    $property = new PropertyDictionaryList($dictionary, 'prop.multi');
                    break;

            }
            $this->registery[$property->get_key()] = $property;
        }

    }

    /**
     * Генерация элементов формы по описанию
     *
     * @return string
     */
    public function render()
    {
        $buf = '';
        foreach ($this->registery as $element) {
            $buf .= $element->render();
        }
        return $buf;
    }

    /**
     *  Сбор правил для валидации ввода из формы
     *
     * @return array
     */
    public function rules()
    {
        $rules = array();
        foreach ($this->registery as $element) {
            if ($element->hasRules()) {
                $rules[$element->prop_key()] = $element->getRules();
            }
        }
        return $rules;
    }

    /**
     * Записать в БД данные полей
     *
     * @param Item $item - модель объявления
     * @param array $request - массив из post-запроса
     * @return void
     */

    public function save(Item $item, array $request)
    {
        foreach ($this->registery as $key => $element) {
            $prop_key = $element->prop_key();
            $parameter = '';
            if (array_key_exists($prop_key, $request)) {
                $parameter = $request[$prop_key];
                if ($element->dictionary->type == 'logical') {
                    $parameter = '1';
                }
            }
            $element->saveForItem($item, $parameter);
        }
    }

    /**
     * Загрузка данных в структуру из БД (ItemValue)
     *
     * @param Item $item
     * @return void
     */
    public function load_values(Item $item)
    {
        // считать параметры из БД
        $values = $item->values()->get();
        foreach ($this->registery as $element) {
            // не найден - null
            $element->value = $values->firstWhere('slug', $element->get_key());
        }
    }

    /**
     *  Создание массива для просмотра параметров
     *
     *
     * @return array
     */
    public function fields()
    {
        $fields = array();
        foreach ($this->registery as $element) {
            $fields[$element->prop_key()] = $element->get_label_field();
        }
        return $fields;
    }

}

