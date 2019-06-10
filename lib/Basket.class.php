<?php

class Basket
{
    /**
     * info basket user who authorized
     * @return mixed
     */
    public static function basketInfo()
    {
        if (empty($_SESSION['basket'])) {
            self::cookieSessionBasket();
        } else {
            self::fromSessionToCookieBasket();
        }

        if (Auth::getUserInfo()) {

            $basket = self::showBasketUser();

            self::dbCookieSessionBasket($basket);

            return $basket;
        } else {

            return $_SESSION['basket'];
        }
    }

    /**
     * getting basket from cookie and put in session
     */
    private function cookieSessionBasket()
    {

        $cookieBasket = $_COOKIE['basket'];
        $sessionBasket = $_SESSION['basket'];

        foreach ($cookieBasket as $key => $count) {

            if (array_key_exists($key, $sessionBasket)) {
                $sessionBasket[$key]['count'] = $count;
            } else {
                $product = db::getInstance()->SelectRow("select * from products where ID_UUID = :uid", ['uid' => $key]);
                $sessionBasket[$key] = array_merge($product, ['count' => $count]);
            }
        }
        $_SESSION['basket'] = $sessionBasket;
    }

    /**
     * get from session and put into cookie
     */
    public static function fromSessionToCookieBasket()
    {
        $sessionBasket = $_SESSION['basket'];
        $cookieBasket = $_COOKIE['basket'];

        foreach ($sessionBasket as $uid => $product)
            self::setCookieBasket($uid, $product['count']);

        foreach ($cookieBasket as $key => $count)
            if (!array_key_exists($key, $sessionBasket))
                self::deleteCookieBasket($key);
    }


    /**
     * @param $basket products from table basket of authorized user
     * if we don't have some products in table basket, we put that products from session to this table
     */
    private function dbCookieSessionBasket($basket)
    {

        $sessionBasket = $_SESSION['basket'];

        foreach ($sessionBasket as $k => $val)

            if (!array_key_exists($k, $basket))
                self::addGoods($k, $val['count'], $prim = 'added with ' . __FUNCTION__, true);


        foreach ($basket as $key => $item) {

            if (!array_key_exists($key, $sessionBasket)) {
                $sessionBasket[$key] = $item;
            } else {
                if ($sessionBasket[$key]['count'] != $item['count'])
                    $sessionBasket[$key]['count'] = $item['count'];
            }
        }
        $_SESSION['basket'] = $sessionBasket;
    }

    /**
     * set $_COOKIE['basket']
     * @param $uid ID_UUID of product
     * @param int $value amount of product in basket [optional]
     */
    public static function setCookieBasket($uid, $value = 1)
    {
        setcookie("basket[$uid]", $value, TIME_COOKIE_BASKET, '/');
    }

    /**
     * delete from $_COOKIE['basket'] product with $uid
     * @param $uid ID_UUID of product
     * @param int $value amount of product in basket [optional]
     */
    public static function deleteCookieBasket($uid, $value = 1)
    {
        setcookie("basket[$uid]", $value, DELETE_COOKIE_BASKET, '/');
    }

    /**
     * unset $_COOKIE['basket']
     */
    public static function clearCookieBasket()
    {
        foreach ($_COOKIE['basket'] as $uid => $value) {
            setcookie("basket[$uid]", $value, DELETE_COOKIE_BASKET, '/');
        }
    }

    /**
     * @param $idUser user id [optional], if not isset $idUser,
     * we use id user who authenticated now
     * @return array of products which authenticated user putted in basket,
     * else return array of products from $_SESSION
     */
    public static function showBasketUser($idUser = null)
    {
        if ($idUser == null) {
            $idUser = Auth::getIdUser();
        }

        if (!empty($idUser)) {

            $sql = "select products.id, products.name, products.img, products.price, products.id_category, products.status,
                  products.status, products.view, products.salePrice, products.date, products.description,
                  products.short_description, products.ID_UUID, basket.count, users.id_user, users.name as name_user, 
                  users.surname, users.login
                  from basket
                  inner join products on basket.ID_UUID = products.ID_UUID
                  inner join users on basket.id_user = users.id_user
                  where basket.id_user = :id_user";

            $basketDb = db::getInstance()->Select($sql, ['id_user' => $idUser]);

            return Functions::mapArray('ID_UUID', $basketDb);
        } else {
            return $_SESSION['basket'];
        }
    }

    /**
     * Clear $_SESSION['basket'] and $_COOKIE['basket ']
     */
    public static function clearBasket()
    {
        self::clearCookieBasket();
        unset($_SESSION['basket']);
    }


    public static function showUsersWithBaskets()
    {
        $sql = "select users.id_user, users.name, users.surname, users.login, basket.*  from basket 
                    inner join users on users.id_user = basket.id_user";
        $users = db::getInstance()->Select($sql);

        return Functions::mapArray('login', $users);
    }

    /**
     * @param $id of product which you get from post with ajax
     * @return count of product from table basket
     */
    public static function thisProductCount($id)
    {
        if (isset($_POST['id'])) {
            (int)$id = $_POST['id'];
        }
        $userId = Auth::getIdUser();
        $sql['sql'] = "select count from basket where ID_UUID = (select ID_UUID from products where products.id = :id) and id_user =:user_id";
        $sql['params'] = [
            'id' => $id,
            'user_id' => $userId
        ];
        $count = db::getInstance()->Select($sql['sql'], $sql['params']);
        return $count;
    }

    /**
     * @param $uid ID_UUID товара
     * @param int $count_goods Колличество товаров в корзине
     * @param string $prim Комментарии при добавлении в корзину
     * @param bool $isAuth Авторизован ли пользователь
     * если пользователь аутентифицирован то добавляет в БД если нет то в сессии
     */
    public static function addGoods($uid, $count_goods = 1, $prim = 'Added with ajax', $isAuth = false)
    {

        $countProducts = (int)$count_goods;

        if ($isAuth) {
            $idUserSession = $_SESSION['IdUserSession'];
            // Создадим ззапрос для проверки наличия записи в БД
            $sql['sql'] = "select * from basket 
                        where ID_UUID = :uid 
                        and id_user = (select id_user from users_auth where hash_cookie = :idUserSession)";
            $sql['param'] =
                [
                    'uid' => $uid,
                    'idUserSession' => $idUserSession,
                ];

            $productBasket = db::getInstance()->SelectRow($sql['sql'], $sql['param']);

            $id = $productBasket['id'];
            if ($productBasket) //Если товар имеется в корзине
            {
                $sql['sql'] = "update basket set count = count + 1 where id = :id";
                $sql['param'] =
                    [
                        'id' => $id,
                    ];
                db::getInstance()->Query($sql['sql'], $sql['param']);
            } else {
                $sql['sql'] = "insert into basket (ID_UUID, count, prim, id_user) 
                            value (:uid, :count_products, :prim,(select id_user from users_auth 
                                                                where hash_cookie = :idUserSession));";
                $sql['param'] =
                    [
                        'uid' => $uid,
                        'count_products' => $countProducts,
                        'idUserSession' => $idUserSession,
                        'prim' => $prim
                    ];
                db::getInstance()->Query($sql['sql'], $sql['param']);
            }
        } else {
            $basket = $_SESSION['basket'];

            if (array_key_exists($uid, $basket)) {
                $basket[$uid]['count'] += 1;
            } else {
                $product = db::getInstance()->SelectRow("select * from products where ID_UUID = :uid", ['uid' => $uid]);
                $basket[$uid] = array_merge($product, ['count' => $countProducts]);
            }
            $_SESSION['basket'] = $basket;
        }
    }

    /**
     * @param $uid ID_UUID product
     * @param  $idUser id user [optional]
     * удаляем товар из бд или из сессии если пользователь не аутентифицирован
     */
    public static function deleteOneProduct($uid, $idUser = null)
    {

        $auth = Auth::getUserInfo();

        if ($auth) {
            $idUserSession = $_SESSION['IdUserSession'];
            if (!isset($idUser)) {
                $sqlUserId = "select id_user from users_auth where hash_cookie = :hash_cookie";
                $idUser = db::getInstance()->SelectRow($sqlUserId, ['hash_cookie' => $idUserSession])['id_user'];
            }

            $sql['sql'] = "select count from basket where ID_UUID = :uid and id_user = :id_user";
            $sql['params'] = [
                'uid' => $uid,
                'id_user' => $idUser,
            ];

            $count = db::getInstance()->SelectRow($sql['sql'], $sql['params'])['count'];

            if ($count == 1 || $count < 1) {

                $sql['sql'] = "delete from basket where ID_UUID = :uid and id_user = :id_user";
                $sql['params'] = [
                    'uid' => $uid,
                    'id_user' => $idUser,
                ];

                db::getInstance()->Query($sql['sql'], $sql['params']);
                unset($_SESSION['basket'][$uid]);
                self::deleteCookieBasket($uid);

            } else {

                $sql['sql'] = "update basket set count = count - 1 WHERE ID_UUID = :uid and id_user = :id_user";
                $sql['params'] = [
                    'uid' => $uid,
                    'id_user' => $idUser,
                ];
                db::getInstance()->Query($sql['sql'], $sql['params']);
            }
        } else {

            if (array_key_exists($uid, $_SESSION['basket'])) {

                if ($_SESSION['basket'][$uid]['count'] == 1 || $_SESSION['basket'][$uid]['count'] < 1) {
                    unset($_SESSION['basket'][$uid]);
                    self::deleteCookieBasket($uid);
                } else {
                    $_SESSION['basket'][$uid]['count'] -= 1;
                }
            }
        }
    }
}
