<?php

namespace App\ViewModels;

use App\Models\Dictionary;
use App\Models\ItemValue;
use function Symfony\Component\String\b;

class PropertyDictionaryBinary extends PropertyDictionary
{

    protected function load_variants()
    {
        return [$this->dictionary->binary1, $this->dictionary->binary2];
    }

    protected function saveAttribute(ItemValue $value, $parameter)
    {
        $value->binary = intval($parameter);
        switch ($value->binary) {
            case 1:
                $value->text = $this->dictionary->binary1;
                break;
            case 2:
                $value->text = $this->dictionary->binary2;
                break;
        }
    }

    public function field()
    {
        return empty($this->value) ? '' : $this->value->text;
    }

    public function field_key()
    {
        return empty($this->value) ? '' : $this->value->binary;
    }


}
