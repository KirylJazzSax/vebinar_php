<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 27.04.2019
 * Time: 11:31
 */

class Search
{
    /**
     * @param $name name of product for search in table products
     * @return array with products
     */
    public static function searchProducts($name)
    {

        $idUser = Auth::getIdUser();

        $sql = "select products.*, basket.count from products 
                  left join basket on basket.ID_UUID = products.ID_UUID
                  and basket.id_user = '$idUser'
                  where lower(name) like '%$name%'";

        return $result = db::getInstance()->Select($sql);
    }
}