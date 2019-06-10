<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.04.2019
 * Time: 12:46
 */

class AdminController extends Controller
{
    public $view = 'admin';

    public function index() {

        if (!Auth::isAdmin()) {
            return self::not_admin();
        }

        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }

    public function basket() {

        if (!Auth::isAdmin()) {
            return self::not_admin();
        }

        if (isset($_GET['id'])) {
            $controller = new BasketController();
            $controller->index($_GET['id']);
        } else {
            $this->view .= "/" . __FUNCTION__ . ".php";
            echo $this->controller_view($_GET['id']);
        }

    }

    public function add_product() {

        if (!Auth::isAdmin()) {
            return self::not_admin();
        }

        $this->view .= "/" . __FUNCTION__ . ".php";
        echo $this->controller_view();
    }

    public function users() {

        if (!Auth::isAdmin()) {
            return self::not_admin();
        }

        $this->view .= "/" . __FUNCTION__ . ".php";
        echo $this->controller_view();
    }

    public function edit() {

        if (!Auth::isAdmin()) {
            return self::not_admin();
        }

        $this->view .= "/" . __FUNCTION__ . ".php";
        echo $this->controller_view();
    }
}