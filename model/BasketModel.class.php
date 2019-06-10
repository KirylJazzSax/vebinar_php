<?php

class BasketModel extends Model
{
    public $view = 'basket';
    public $title;

    function __construct()
    {
        parent::__construct();
        $this->title .= "Корзина";
        $this->model = __CLASS__;
    }

    function index($idUser = null, $deep = 0)
    {
        $this->method = __FUNCTION__;
        return Basket::showBasketUser($idUser);
    }

    function edit($idUser)
    {
        if (!empty($idUser)) {
            return Basket::showBasketUser($idUser);
        } else {
            return Basket::showUsersWithBaskets();
        }
    }
}
