<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.04.2019
 * Time: 9:48
 */

class ArticlesController extends Controller
{
    public $view = 'articles';

    function __construct(string $message = '')
    {
        parent::__construct($message);
    }

    function index()
    {
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }

    function articles()
    {
        if (isset($_GET['id'])) {
            $this->view .= "/" . __FUNCTION__ . '.php';
            echo $this->controller_view($_GET['id']);
        } else {
            $msg = 'Статья не найдена!';
            self::info($msg);
        }
    }
}