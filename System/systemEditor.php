<?php

class systemEditor
{
    function __construct()
    {
    }

    function __destruct()
    {
    }

    function change_key($array, $old_keyArr, $new_keyArr) {
        if(count($old_keyArr) != count($new_keyArr)) {
            return $array;
        } else {
            foreach($old_keyArr as $key => $old_key) {
                if( ! array_key_exists($old_key, $array)) {
                    return $array;
                } else {
                    $keys = array_keys($array);
                    $keys[array_search($old_key, $keys)] = $new_keyArr[$key];
                    $array = array_combine($keys, $array);
                }
            }
            return $array;
        }
    }
}
