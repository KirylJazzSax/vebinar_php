<?php

class GoodController extends Controller
{

public $view = 'catalog';

public function good()
{
    $product = Product::getProduct($_GET['id']);
    Debug::Deb($product);
    if ($product) {
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view($product);
    } else {
        $msg = 'Товар не найден!';
        self::info($msg);
    }
}

public function __call($methodName, $args) 
{
    $result = $this->sub_catalog($args[0]);
	return $result; 
}

public function sub_catalog($data)
{
	$this->view .= "/" . __FUNCTION__ . '.php';	
	echo $this->controller_view();	
}

public function add($product = null)
{
    if (Auth::isAdmin()) {
        if ($_POST['add_product']) {
            if (Product::addProduct()) {
                $msg = 'Вы успешно добавили товар';
            }else{
                $msg = 'Что-то пошло не так! Попробуйте еще раз!';
            }
            return self::info($msg);
        }
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view($product);
    } else {
        return self::not_admin();
    }
}

public function edit()
{
    if (Auth::isAdmin()) {

        $product = Product::getProduct($_GET['id']);

        if (isset($_POST['edit_product'])) {
            if (Product::editProduct($_POST['uid'])) {

                $msg = 'Вы успешно поменяли данные товара';
                return self::info($msg);
            } else {

                $msg = 'Что то пошло не так, попробуйте еще раз!';
                return self::info($msg);
            }
        }
        if (!empty($product)){

            self::add($product);

        } else {
            $msg = 'Не удалось найти товар!';
            return self::info($msg);
        }
    } else {
        return self::not_admin();
    }
}

}