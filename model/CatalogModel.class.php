<?php

class CatalogModel extends Model
{
    public $view = 'catalog';
    public $title;

    function __construct()
    {
        parent::__construct();
        $this->title .= "Каталог";
    }

    function index($data = null, $action = 0)
    {
        return Product::getCategories();
    }

    function __call($methodName, $args)
    {
        if (empty($_GET['id'])) {
            return $this->sub_catalog($_GET['action']);
        } else {
            return $this->goods($_GET['id']);
        }
    }

    function sub_catalog($data, $start = 0, $count = 5)
    {
        $action = $data ? $data : null;
        return Product::getCategories($action);
    }

    function goods($data, $start = 0, $count = 5)
    {
        return Product::getProducts($data);
    }
}
