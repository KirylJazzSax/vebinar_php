<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 17.04.2019
 * Time: 13:14
 */

class AdminModel extends Model
{
    public $view = 'admin';
    public $title;

    function __construct()
    {
        parent::__Construct();
        $this->title .= "Админ";
    }


    function index($data = NULL, $deep = 0) {}

    function basket($idUser = null)
    {
        if (!empty($idUser)) {
            return Basket::showBasketUser($idUser);
        }
        return Basket::showUsersWithBaskets();
    }

    function add_product()
    {
        $result = Product::getCategories();

        if ($_POST['add_product']) {
            if (Product::addProduct()) {
                echo 'yes';
            } else {
                echo 'something_wrong';
            }
        }

        Debug::Deb($result);
        Debug::Deb($_FILES);
        return $result;
    }

    function edit(int $nStart = 0, int $count = 5)
    {
        $this->view .= "/" . __FUNCTION__ . '.php';

        $nEnd = $count + $nStart;

        $result['catalog'] = db::getInstance()->Select("select * from products order by date desc limit $nStart, $count");

        $sql = "select count(*) as count_record from products";

        $count_record = db::getInstance()->SelectRow($sql)['count_record'];

        $out_row = $count_record <= $nEnd ? false : true;

        $result['out_row'] = $out_row;
        $result['current_record'] = $nEnd;
        $result['count_product'] = $count;

        if (isset($_POST['edit_product'])) {

            if (Product::editProduct($_POST['uid'])) {
                header("Location:" . Config::get('domain') . "/admin/edit");

            } else {
                echo 'something wrong';
            }
        }
        return $result;
    }
}