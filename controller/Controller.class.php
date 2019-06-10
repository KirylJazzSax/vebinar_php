<?php

class Controller
{
    public $view = 'index';
    protected $data;
    protected $template;
    protected $role = 1;

    function __construct($message = '')
    {
        $this->data = [
            'domain' => Config::get('domain'),
            'page' => $_GET['page'],
            'action' => $_GET['action'],
            'BreadCrumbs' => Bread::BreadCrumbs(explode("/", $_SERVER['REQUEST_URI'])),
            'isAuth' => Auth::logIn()[0],
            'role' => Auth::getRole(),
            'role_info' => Auth::getRoleInfo(),
            'microtime' => microtime(true),
            'basket' => Basket::basketInfo(),
            'title' => Config::get('sitename'),
            'img' => Config::get('img'),
            'insta' => Instagram::Insta(),
            'navigation' => Navigation::navigationMenu(),
            'message' => $message,
            'new_products' => Functions::mapArray('ID_UUID', Product::newProduct()),
            'top_products' => Functions::mapArray('ID_UUID', Product::topProduct()),
            'sale_products' => Functions::mapArray('ID_UUID', Product::statusProduct()),
        ];
    }

    function getData()
    {
        return [
            'domain' => $this->data['domain'],
            'img' => $this->data['img'],
            'role_info' => $this->data['role_info'],
            'new_products' => $this->data['new_products'],
        ];
    }

    public function controller_view($param = 0)
    {
        $modelName = $_GET['page'] . 'Model';
        $methodName = isset($_GET['action']) ? $_GET['action'] : 'index';

        Debug::Deb($_SERVER['REQUEST_URI']);
        Debug::Deb($this->data['BreadCrumbs']);
        if (class_exists($modelName)) {
            $model = new $modelName();
            $content_data = $model->$methodName($param);
        }

        $this->data['title'] = $model->title;
        $this->data['model'] = $model->model;
        $this->data['template'] = $model->template;
        $this->data['out_row'] = $model->out_row;
        $this->data['method'] = $model->method;
        $this->data['current_record'] = $model->current_record;
        $this->data['data'] = $model->data;
        $this->data['content_data'] = $content_data;

        $loader = new Twig_Loader_Filesystem(Config::get('path_templates'));
        $twig = new Twig_Environment($loader);
        $template = $twig->loadTemplate($this->view);

        return $template->render($this->data);

    }

    public function info($message)
    {
        self::__construct($message);
        $this->view = 'index';
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }

    public function not_admin()
    {
        $this->view = 'index';
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }

    public function not_auth()
    {
        $this->view = 'index';
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }

    public function not_editor()
    {
        $this->view = 'index';
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }

    public function __call($name, $param)
    {
        header("Location: /page404");
    }

}