<?php

class RegisterController extends Controller
{
    public $view = 'register';

    public function index()
    {
        if (isset($_POST['login'])) {

            $sql = "select login from `users` where `login` = :login";
            $login = db::getInstance()->SelectRow($sql, ['login' => $_POST['login']]);

            if (empty($login) && $_POST['pass'] == $_POST['pass2']) {
                if (Auth::registerUser()) {
                    $msg = 'Регистрация прошла успешно!';
                    self::info($msg);
                } else {
                    $msg = 'Попробуйте ещё раз, что-то пошло не так!';
                    self::info($msg);
                }
            } else {
                $msg = 'Пользователь с таким логином уже сущществует! Попробуйте еще раз.';
                self::info($msg);
            }
        } else {
            $this->view .= "/" . __FUNCTION__ . '.php';
            echo $this->controller_view();
        }
    }

}