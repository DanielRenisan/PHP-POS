<?php

if (!function_exists('api_url')) {

    function api_url($endUrl)
    {
        $baseUrl = 'http://localhost/rest/public/api/';
        $fullUrl = $baseUrl . $endUrl;
        return  $fullUrl;
    }
}
if (!function_exists('removeElementWithValue')) {
    function removeElementWithValue($array, $key, $value)
    {
        foreach($array as $subKey => $subArray){
            if($subArray[$key] == $value){
                unset($array[$subKey]);
            }
        }
        return $array;
    }
}

if (!function_exists('get_sub_menu')) {
    function get_sub_menu($array, $id)
    {
        foreach($array as $subKey => $subArray){
            if($subArray['menu']['parent_id'] !== $id || $subArray['menu']['parent_id'] == null){
                unset($array[$subKey]);
            }
        }
        return $array;
    }
}

if (!function_exists('get_default_menu')) {
    function get_default_menu($array)
    {
        foreach($array as $subKey => $subArray){
            if($subArray['type'] !== 'DF'){
                unset($array[$subKey]);
            }
        }
        return $array;
    }
}