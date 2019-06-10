<?php

class GoodModel extends Model
{
	public $view = 'good';
	public $title;
	
	function __construct()
	{
		parent::__construct();
		$this->title .= "Товар";
	}

	public function good($product)
	{
	    if (!isset($_POST['PageAjax']))
		db::getInstance()->Query('update `products` set `view` = `view` + 1 where id = :id', ['id' => $product[0]['id']]);

		return $product;
	}

	public function add($product)
    {
        return [
            'product' => $product,
            'categories' => Product::getCategories(),
        ];
    }

	public function edit($product)
    {
        return [
            'product' => $product,
            'categories' => Product::getCategories(),
        ];
    }
}