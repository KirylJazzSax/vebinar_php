<?php
/**
 * 
 */
class PersonalController extends Controller
{
	public $view = 'personal';

	public function index()
	{
	    $user = Auth::getUserInfo();
	    if ($user) {
		    $this->view .= "/" . __FUNCTION__ . '.php';
		    echo $this->controller_view($user);
        } else {
	        self::not_auth();
        }
	}

	public function edit() {

        $user = Auth::getUserInfo();

	    if ($user) {
	        if (!empty($_POST['pass'])) {
	            if (Auth::updateUser($_POST['pass'])) {
                    $msg = 'Вы успешно обновии свой профиль!';
                    return self::info($msg);
                } else {
                    $msg = 'Вам не удалось обновить профиль! Возможно пароль введенный вами неверный!';
                    return self::info($msg);
                }
            }
        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view($user);

        } else {
	        self::not_auth();
        }
    }
}