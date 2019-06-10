<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.05.2019
 * Time: 14:36
 */

class UserController extends Controller
{
    public $view = 'user';

    function index()
    {
        if (Auth::isAdmin()) {
            $this->view .= "/" . __FUNCTION__ . '.php';
            echo $this->controller_view();
        } else {
            self::not_admin();
        }
    }

    function user()
    {
        if (Auth::isAdmin()) {
            $user = Auth::getUserInfo($_GET['id']);
            if ($user) {
                $this->view .= "/" . __FUNCTION__ . '.php';
                echo $this->controller_view($user);
            } else {
                $msg = 'Пользователь с данным id не найден!';
                self::info($msg);
            }
        } else {
            self::not_admin();
        }
    }

    function edit()
    {
        if (Auth::isAdmin()) {

            if (!empty($_POST['pass']) && !empty($_POST['id_user'])) {
                if (Auth::updateUser($_POST['pass'], $_POST['id_user'])) {
                    $msg = 'Вы изменили профиль пользователя с логином ' . $_POST['login'];
                    return self::info($msg);
                } else {
                    $msg = 'Что-то пошло не так, вам не удалось изменить пользователя с логином '. $_POST['login'];
                    return self::info($msg);
                }
            }

            if (!empty(Auth::getUserInfo($_GET['id']))) {
                $this->view .= "/" . __FUNCTION__ . '.php';
                echo $this->controller_view(Auth::getUserInfo($_GET['id']));
            } else {
                $msg = 'Пользователь с таким id не найден!';
                self::info($msg);
            }
        } else {
            self::not_admin();
        }
    }

    function basket()
    {
        if (Auth::isAdmin()) {
            if (!empty($_GET['id'])) {
                $this->view .= "/" . 'id' . '.php';
                echo $this->controller_view($_GET['id']);
            } else {
                $this->view .= "/" . __FUNCTION__ . '.php';
                echo $this->controller_view();
            }
        } else {
            self::not_admin();
        }
    }
}