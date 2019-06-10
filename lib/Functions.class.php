<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 19.05.2019
 * Time: 21:00
 */

class Functions
{
    /**
     * This function for multidimensional array. Function sets keys arrays in multidimensional array
     * @param $key key for array inside $array
     * @param $array multidimensional array
     * @return multidimansional array with seated $key for each array inside
     */
    public static function mapArray($key, $array) {

        foreach ($array as $item) {
            $result[$item[$key]] = $item;
        }

        return $result;
    }
}