<?php

namespace App\ViewModels;

use App\Models\ItemValue;

class PropertyDictionaryLogical extends PropertyDictionarySimple
{

    public function field()
    {
        return empty($this->value) ? 'нет' : 'да';
    }
}
