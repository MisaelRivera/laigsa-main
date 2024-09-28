<?php
    function removeDynamicPostfixFromKeys(array $array): array
    {
        $result = [];
    
        foreach ($array as $key => $value) {
            // Use regex to check if the key ends with a numeric postfix like _1, _2, etc.
            $newKey = preg_replace('/_\d+$/', '', $key);
    
            // Add the modified key-value pair to the result array
            $result[$newKey] = $value;
        }
    
        return $result;
    } 