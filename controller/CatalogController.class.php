<?php

class CatalogController extends Controller
{
    public $view = 'catalog';

    function index()
    {
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }

    function __call($methodName, $args)
    {
        if (isset($_GET['id'])) {
            return $this->goods();
        } else {
            return $this->sub_catalog($args[0]);
        }
    }

    function sub_catalog()
    {
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }

    function goods()
    {
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }
}