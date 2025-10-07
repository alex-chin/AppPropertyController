<?php

namespace App\ViewModels;


class PropertyDictionaryRange extends PropertyDictionaryNumber
{

    protected function collectRules()
    {
        parent::collectRules();
        $this->rules[] = "min:{$this->dictionary->number_min}";
        $this->rules[] = "max:{$this->dictionary->number_max}";
    }
}
