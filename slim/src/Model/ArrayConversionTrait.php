<?php

namespace BlackScorp\Movies\Model;

trait ArrayConversionTrait
{
    public function toArray():array{
        $properties = get_object_vars($this);
        foreach($properties as $name => $value){
            if(is_bool($value)){
                $properties[$name] = (int)$value;
            }
        }

        return $properties;
    }
}