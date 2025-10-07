<?php

namespace App\ViewModels;

use App\Models\ItemValue;

class PropertyDictionaryNumber extends PropertyDictionary
{
    protected function collectRules()
    {
        parent::collectRules();
        $this->rules[] = "integer";
    }

    protected function saveAttribute(ItemValue $value, $parameter)
    {
        $value->number = $parameter;
    }

    /**
     * Простой показ названия поля и значения
     * @return mixed
     */
    public function field()
    {
        return empty($this->value) ? '' : $this->value->number;
    }
}
