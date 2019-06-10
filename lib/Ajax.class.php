<?php

class Ajax
{

    public static $views;
    public $page;
    public $action;

    function __construct()
    {
        $this->page = $_GET['page'];
        $this->action = $_GET['action'];
    }

    /**
     * Ajax authentication
     * @return array which contains info about user and his role.
     */
    public static function register()
    {
        self::$views = 'auth.html';
        return [
            'isAuth' => Auth::logIn($_POST['login'], $_POST['pass'], $_POST['rememberme']),
            'role' => Auth::getRole(),
        ];
    }

    /**
     * Function for validation register form
     * @return login if it exists
     */
    public static function checkLogin()
    {
        self::$views = 'login.html';

        $user = db::getInstance()->SelectRow("select login from users where login = :login", ['login' => $_POST['login']])['login'];

        return [
            'login' => $user,
        ];

    }

    /**
     * Function adds good in basket
     * @return GoodModel, show good with count on success
     */
    public static function addGoods()
    {
        self::$views = 'product.html';

        $model = new GoodModel();

        $uid = $_POST['uid'];

        $isAuth = Auth::logIn();

        if ($isAuth) {
            $count = Product::getProduct($_POST['id'])[0]['count'];
            $count = ($count) ? (int)$count : 1;
        } else {
            $count = $_SESSION['basket'][$uid]['count'];
            $count = ($count) ? (int)$count : 1;
        }

        Basket::addGoods($uid, $count, $_POST['prim'], $isAuth);

        return [
            'content_data' => $model->good(Product::getProduct($_POST['id'])),
        ];
    }

    /**
     * for buttons see more or for pages
     * @return array with model and method
     */
    public static function seeMore()
    {
        // template for load
        self::$views = $_POST['view'] . '.html';

        // model and method
        $model = new $_POST['model']();
        $method = $_POST['method'];

        // when $_POST['page'] we use pages for show content,
        // otherwise button see more
        if (is_numeric($_POST['page'])) {
            $start = $_POST['page'] - 1;
        } else {
            $start = $_POST['current_record'];
        }

        // id editor
        $data = $_POST['editor'] ? $_POST['editor'] : null;

        return [
            'content_data' => $model->$method($data, $start),
            'editor' => $data,
            'model' => $model->model,
            'current_record' => $model->current_record,
            'out_row' => $model->out_row,
            'method' => $model->method,
            'template' => $model->template,
        ];
    }

    /**
     * delete product from basket
     * @return array with model and method
     */
    public static function deleteGood()
    {
        // template for load
        self::$views = 'basket.html';
        // model and method for load
        $model = new $_POST['model']();
        $method = $_POST['method'];
        // delete product from basket with id $user,
        // otherwise with id authenticated user
        $user = $_POST['user'] ? $_POST['user'] : null;
        Basket::deleteOneProduct($_POST['uid'], $user);

        return [
                'content_data' => $model->$method($user),
                'model' => $model->model,
                'method' => $model->method,
        ];
    }

    /**
     * search product in table products column name
     * @return array with search result
     */
    public static function searchProduct()
    {
        // template for load
        self::$views = 'search.html';

        // what we search
        $nameProduct = mb_strtolower($_POST['name']);

        return [
            'search' => Search::searchProducts($nameProduct),
            'img' => Config::get('img'),
            'domain' => Config::get('domain'),
        ];
    }
}




// ---------------------------- useless code -------------------------------------------


//public static function update()
//{
//    self::$views = 'edit_user.html';
//    if (Auth::updateUser($_POST['pass'])) {
//        return [
//            'user' => Auth::logIn(),
//            'message' => 'Вы изменили свои данные!'
//        ];
//    }else{
//        return [
//            'user' => Auth::logIn(),
//            'message' => 'Вы ввели неверный пароль!'
//        ];
//    }
//
//}

//public static function see_additional_goods()
//{
//	self::$views = 'catalog/product_catalog.php';
//	$model = new catalogModel();
//	$nStart = $_POST['current_record'];
//	$count = $_POST['count'];
//	$data = $_POST['category'];
//	return ['content_data' => $model->sub_catalog($data, $nStart, $count)];
//}

//public static function see_additional_goods()
//{
//	self::$views = 'galery/ajaxgallery.html';
//	$model = new GaleryModel();
//	$nStart = $_POST['current_record'];
//	$count = $_POST['count'];
//	$data = $_POST['category'];
//	return ['content_data' => $model->galeryajax($nStart, $count),
//            'domain' => Config::get('domain'),
//            'UPLOAD_DIR' => Config::get('UPLOAD_DIR'),
//    ];
//}


//public function testAjax(){
//
//    self::$views = 'add_article.html';
//    if (isset($_POST['msg'])) {

//    }
//
//    return ['message' => 'it works'];
//}

//public function showArticle() {
//
//    self::$views = 'article.html';
//    $sql = "update `articles` set `view` = `view` + 1 where `id_article` = :id_article";
//    db::getInstance()->Query($sql, ['id_article' => $_POST['id_article']]);
//
//    return [
//        'articles' => Articles::showArticle($_POST['id_article']),
//        'editor' => Auth::isEditor(),
//        'admin' => Auth::isAdmin(),
//        'domain' => Config::get('domain'),
//        'img' => Config::get('img'),
//    ];
//}
//
//public function editArticle() {
//
//    self::$views = 'add_article.html';
//
//    $sql = "select * from articles where id_article = :id_article";
//
//    return [
//        'article' => db::getInstance()->SelectRow($sql, ['id_article' => $_POST['id_article']]),
//        'img' => Config::get('img'),
//        'domain' => Config::get('domain'),
//    ];
//}

//public static function showUsersWithBasket() {
//
//    self::$views = 'admin/users_basket.php';
//
//    return [
//        'content_data' => Basket::catalogBasket(),
//    ];
//}

//public static function showProduct()
//{
//    self::$views = 'add_product.html';
//
//    $sql['sql'] = "select * from products where ID_UUID = :uid";
//    $sql['param'] = [
//        'uid' => $_POST['uid'],
//    ];
//
//    $product = db::getInstance()->SelectRow($sql['sql'],$sql['param']);
//
//    return [
//        'content_data' => Product::getCategories(),
//        'product' => $product,
//        'domain' => Config::get('domain'),
//        'img' => Config::get('img'),
//        'link' => 'Назад к товарам',
//    ];
//}

//public static function showUserInfo()
//{
//    self::$views = 'edit_user.html';
//    $result['user_info'] = Auth::getUserInfo($_POST['user']);
//
//    return [
//        'content_data' => $result,
//        'domain' => Config::get('domain'),
//        'img' => Config::get('img'),
//        'link' => 'Назад к пользователям?',
//        'id_user' => $_POST['user'],
//    ];
//}

//public static function showUserProducts()
//{
//    self::$views = 'user_products.html';
//
//    return [
//        'content_data' => Basket::catalogBasket(),
//        'img' => Config::get('img'),
//        'domain' => Config::get('domain'),
//    ];
//}

//public static function seeAdditionalProducts() {
//    self::$views = 'edit_product.html';
//    $model = new AdminModel();
//    $nStart = $_POST['current_record'];
//    $count = $_POST['count'];
//    $data = $_POST['category'];
//    return [
//        'content_data' => $model->edit($nStart, $count),
//        'domain' => Config::get('domain'),
//        'img' => Config::get('img'),
//    ];
//}