<?php

namespace App\Service;

class ArrayFlattener
{
    function flattenArray($arr)
    {
        $result = [];
        foreach ($arr as $item) {
            if (is_array($item)) {
                $result = array_merge($result, self::flattenArray($item));
            } else {
                $result[] = $item;
            }
        }
        return $result;
    }
}
