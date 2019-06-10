<?php

class BasketController extends Controller
{
    public $view = 'basket';

    function index($idUser = null)
    {
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view($idUser);
    }

    function __call($methodName, $args)
    {
        $result = $this->sub_catalog($args[0]);
        return $result;
    }

    function sub_catalog($data)
    {
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }

    function edit()
    {
        if (Auth::isAdmin()) {
            if (isset($_GET['id'])) {
                return self::index($_GET['id']);
            } else {
                self::not_admin();
            }
        }
    }
}