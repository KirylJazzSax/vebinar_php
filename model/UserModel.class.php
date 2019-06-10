<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 12.05.2019
 * Time: 19:54
 */

class UserModel extends Model
{
    public $view = 'user';
    public $title;

    function __construct()
    {
        parent::__construct();
        $this->title .= 'Пользователи';
        $this->model = __CLASS__;
    }

    function index($data = NULL, $page = 0, $count = 5)
    {
        $currentPage = $page * $count;

        $countUsers = db::getInstance()->SelectRow("select count(*) as count_users from users")['count_users'];

        $pages = ceil($countUsers / $count);

        for ($i = 0; $i < (int)$pages; $i++)
            $outRow[] = $i+1;

        $this->method = __FUNCTION__;
        $this->out_row = $outRow;
        $this->template = 'users';
        $this->current_record = $currentPage;

        Debug::Deb(Auth::getUserInfo(9));

        return Auth::showUsers($currentPage, $count);
    }

    function user($user)
    {
        return [
            'user' => $user,
        ];
    }

    function edit($user)
    {
        return [
            'user' => $user
        ];
    }

    function basket($idUser = null)
    {
        if (!empty($idUser))  {
            $this->method = __FUNCTION__;
            return Basket::showBasketUser($idUser);
        } else {
            return Basket::showUsersWithBaskets();
        }
    }
}
