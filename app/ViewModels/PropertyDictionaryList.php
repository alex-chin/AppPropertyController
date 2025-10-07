<?php

namespace App\ViewModels;

use App\Models\ItemValue;

class PropertyDictionaryList extends PropertyDictionary
{

    protected function load_variants()
    {
        $properties = $this->dictionary->properties()->get();
        $keyed = $properties->mapWithKeys(function ($item, $key) {
            return [$item['slug'] => $item['value']];
        });
        return $keyed->all();
    }


    protected function saveAttribute(ItemValue $value, $parameter)
    {
        $property = $this->dictionary->properties()->where('slug', $parameter)->first();
        if ($property) {
            $value->property()->associate($property);
            $value->text = $property->value;
            $value->slug =  $this->dictionary->slug;
            $value->slug_select = $property->slug;
        }
    }

    public function field()
    {
        return empty($this->value) ? '' : $this->value->text;
    }

    public function field_key()
    {
        return empty($this->value) ? '' : $this->value->slug_select;
    }

}
