<?php

namespace App\ViewModels;

use App\Models\ItemValue;
use App\Models\Item;

class PropertyDictionarySimple extends PropertyDictionary
{

    protected function saveAttribute(ItemValue $value, $parameter)
    {
        // $parameter - текст
        $value->text = $parameter;
    }

}
