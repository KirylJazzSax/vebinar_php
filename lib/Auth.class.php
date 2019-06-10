<?php

/*
Класс авторизации пользователей
*/

class Auth
{

    private static $role = 1;
    private static $roleInfo;
    private static $userId;
    private static $login;

    public static function logIn($login = null, $pass = null, $rememberme = false)
    {

        $isAuth = 0;

        if (!empty($login))   //Если попытка авторизации через форму, то пытаемся авторизоваться
        {
            $isAuth = self::authWithCredential($login, $pass, $rememberme);
        } elseif ($_SESSION['IdUserSession'])    //иначе пытаемся авторизоваться через сессии
        {
            $isAuth = self::checkAuthWithSession($_SESSION['IdUserSession']);
        } else // В последнем случае пытаемся авторизоваться через cookie
        {
            $isAuth = self::checkAuthWithCookie();
        }

        if (isset($_POST['ExitLogin'])) {
            $isAuth = self::userExit();
            Basket::clearBasket();
        }

        if ($isAuth) {
            $IdUserSession = $_SESSION['IdUserSession'];


            $sql['sql'] = "select * from users where id_user = (select id_user from users_auth 
                                                                where hash_cookie = :hash_cookie)";
            $sql['param'] =
                [
                    'hash_cookie' => $IdUserSession,
                ];
            $isAuth = db::getInstance()->Select($sql['sql'], $sql['param']);


            $sql['sql'] = "select users.*, roles.value from users 
                            inner join roles on users.id_role = roles.id_role 
                            where id_user = (select id_user from users_auth 
                                              where hash_cookie = :hash_cookie);";
            $sql['param'] =
                [
                    'hash_cookie' => $IdUserSession,
                ];

            self::$role = db::getInstance()->Select($sql['sql'], $sql['param'])[0]['value'];

            $sql = "select info from roles where id_role = :id";

            self::$roleInfo = db::getInstance()->SelectRow($sql, ['id' => self::$role])['info'];

            self::$userId = $isAuth[0]['id_user'];

            self::$login = $isAuth[0]['login'];


        }

        return $isAuth;

    }

    /**
     * @return int id_role from table roles
     */
    public static function getRole()
    {
        return self::$role;
    }

    /**
     * @return role info from table roles
     */
    public static function getRoleInfo()
    {
        return self::$roleInfo;
    }

    /**
     * @return id_user authenticated user
     */
    public static function getIdUser()
    {
        return self::$userId;
    }

    /**
     * @return login authenticated
     */
    public static function getLogin()
    {
        return self::$login;
    }

    /**
     * @return true when authenticated user got role admin
     */
    public static function isAdmin()
    {
        if (self::$roleInfo) {
            if (self::$roleInfo == 'admin') {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @return true when authenticated user got role editor
     */
    public static function isEditor()
    {
        if (self::$roleInfo) {
            if (self::$roleInfo == 'editor') {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param $id id user [optional]
     * @return array info about user with id $id,
     * otherwise about user who authenticated now
     */
    public static function getUserInfo($id = null)
    {

        if (!isset($id)) {

            return self::logIn()[0];

        } else {

            $sql = "select * from users where id_user = :id_user";

//        $user = db::getInstance()->Select($sql, ['id_user' => $id]);
        }

//    return Functions::mapArray('login', $user);
        return db::getInstance()->SelectRow($sql, ['id_user' => $id]);
    }

    /**
     * @param $id user's id [optional]
     * @param $pass user's password
     * @return bool true if success
     */
    public static function updateUser($pass, $id = null)
    {
        $user = self::getUserInfo($id);
        if (!isset($id)) {
            $idUser = self::$userId;
        } else {
            $idUser = $id;
        }

        $passHash = $user['pass'];
        if (self::isAdmin()) {
            $passHash = self::getUserInfo()['pass'];
        }
        $image = Image::downloadImageUser();
        if ($image['image_name']) {
            $nameImg = $image['image_name'];
        } else {
            $nameImg = $user['img'];
        }

        if ($user) {
            if (password_verify($pass, $passHash)) {

                $sql['sql'] = "update users set login = :login, surname = :surname, name = :name, patronymic = :patronymic,
                              telephone = :telephone, email = :email, age = :age, gender = :gender, comments = :comments,
                              sport = :sport, turist = :turist, padi = :padi, travels = :travels, auto = :auto, it = :it, img = :img
                              where id_user = :id_user";
                $sql['param'] = [
                    'login' => $_POST['login'],
                    'surname' => $_POST['surname'],
                    'name' => $_POST['name'],
                    'patronymic' => $_POST['patronymic'],
                    'telephone' => $_POST['telephone'],
                    'email' => $_POST['email'],
                    'age' => $_POST['age'],
                    'gender' => $_POST['gender'],
                    'comments' => $_POST['comments'],
                    'sport' => $_POST['sport'],
                    'turist' => $_POST['turist'],
                    'padi' => $_POST['padi'],
                    'travels' => $_POST['travels'],
                    'auto' => $_POST['auto'],
                    'it' => $_POST['it'],
                    'img' => $nameImg,
                    'id_user' => $idUser,
                ];
                if (db::getInstance()->Query($sql['sql'], $sql['param'])) {
                    return true;
                } else {
                    return false;
                }
            }
        }
    }

    /*
    Осуществляем удаление всех переменных, отвечающих за авторизацию пользователя.
    */
    protected static function userExit()
    {
        //Удаляем запись из БД об авторизации пользователей

        $IdUserSession = $_SESSION['IdUserSession'];

        $sql['sql'] = "delete from users_auth where hash_cookie = :session";
        $sql['param'] =
            [
                'session' => $IdUserSession,
            ];
        db::getInstance()->Query($sql['sql'], $sql['param']);


        //Удаляем переменные сессий
        unset($_SESSION['IdUserSession']);

        //Удаляем все переменные cookie
        setcookie('idUserCookie', '', time() - 3600 * 24 * 7);

        return $isAuth = 0;
    }

    /*Авторизация пользователя
    при использования технологии хэширования паролей
    $username - имя пользователя
    $password - введенный пользователем пароль
    */
    protected static function authWithCredential($username, $password, $rememberme = false)
    {
        $isAuth = 0;

        $login = $username;


        $sql['sql'] = "select id_user, login, pass from users where login = :login";
        $sql['param'] =
            [
                'login' => $login,
            ];
        $user_date = db::getInstance()->Select($sql['sql'], $sql['param']);


        if ($user_date) {
            $passHash = $user_date[0]['pass'];
            $id_user = $user_date[0]['id_user'];
            $idUserCookie = microtime(true) . random_int(100, PHP_INT_MAX); //Используется более сложная функция генерации случайных чисел
            $idUserCookieHash = hash("sha256", $idUserCookie); //Получаем хэш

            if (password_verify($password, $passHash)) {
                $_SESSION['IdUserSession'] = $idUserCookieHash;

                $sql['sql'] = "insert into users_auth (id_user, hash_cookie, date, prim) 
                            values (:id_user, :idUserCookieHash, now(), :idUserCookie)";
                $sql['param'] =
                    [
                        'id_user' => $id_user,
                        'idUserCookieHash' => $idUserCookieHash,
                        'idUserCookie' => $idUserCookie

                    ];

                if (!db::getInstance()->Query($sql['sql'], $sql['param'])) {
                    echo 'something wrong with authentification';
                }


                if ($rememberme == 'true') {
                    setcookie('idUserCookie', $idUserCookieHash, time() + 3600 * 24 * 7, '/');
                }
                $isAuth = 1;
            } else {
                self::userExit();
            }
        } else {
            self::userExit();
        }

        return $isAuth;
    }

    /* Авторизация при помощи сессий
    При переходе между страницами происходит автоматическая авторизация
    */
    protected static function checkAuthWithSession($IdUserSession)
    {

        $isAuth = 0;

        $hash_cookie = $IdUserSession;


        $sql['sql'] = "select users.login, users_auth.* from users_auth INNER JOIN users on users_auth.id_user = users.id_user where users_auth.hash_cookie = :hash_cookie";
        $sql['param'] =
            [
                'hash_cookie' => $hash_cookie,
            ];
        $user_date = db::getInstance()->Select($sql['sql'], $sql['param']);


        if ($user_date) {
            $isAuth = 1;
            $_SESSION['IdUserSession'] = $IdUserSession;

            if ($_COOKIE['idUserCookie'] == $IdUserSession) {
                setcookie('idUserCookie', '', time() - 3600 * 24 * 7, '/');
                setcookie('idUserCookie', $IdUserSession, time() + 3600 * 24 * 7, '/');
            }
        } else {
            $isAuth = 0;
            self::userExit();
        }
        return $isAuth;
    }

    protected static function checkAuthWithCookie()
    {
        $isAuth = 0;

        $hash_cookie = $_COOKIE['idUserCookie'];


        $sql['sql'] = "select * from users_auth where hash_cookie = :hash_cookie";
        $sql['param'] =
            [
                'hash_cookie' => $hash_cookie,
            ];
        $user_date = db::getInstance()->Select($sql['sql'], $sql['param']);


        if ($user_date) {
            self::checkAuthWithSession($hash_cookie);
            $isAuth = 1;
        } else {
            $isAuth = 0;
            self::userExit();
        }

        return $isAuth;
    }

    /**
     * @return true if user registered
     */
    public static function registerUser()
    {

        $image = Image::downloadImageUser()['image_name'];
        if (empty($image)) {
            $image = null;
        }

        $password = password_hash($_POST['pass'], PASSWORD_DEFAULT);


        $sql['sql'] = "INSERT INTO `users` (`login`, `pass`, `prim`, `surname`, `name`, `patronymic`, 
                        `telephone`, `email`, `age`, `gender`, `comments`, `sport`, `turist`, `padi`, `travels`, 
                        `auto`, `it`, `id_role`, `img`) VALUES (:login, :pass, :prim, :surname, :name, 
                        :patronymic, :telephone, :email, :age, :gender, :comments, :sport, :turist, :padi, :travels,
                         :auto, :it, :id_role, :img)";

        $sql['param'] = [
            'login' => $_POST['login'],
            'pass' => $password,
            'prim' => 'add user',
            'surname' => $_POST['surname'],
            'name' => $_POST['name'],
            'patronymic' => $_POST['patronymic'],
            'telephone' => $_POST['telephone'],
            'email' => $_POST['email'],
            'age' => $_POST['age'],
            'gender' => $_POST['gender'],
            'comments' => $_POST['comments'],
            'sport' => $_POST['sport'],
            'turist' => $_POST['turist'],
            'padi' => $_POST['padi'],
            'travels' => $_POST['travels'],
            'auto' => $_POST['auto'],
            'it' => $_POST['it'],
            'id_role' => self::$role,
            'img' => $image,
        ];

        if (db::getInstance()->Query($sql['sql'], $sql['param'])) {
            return true;
        }
    }

    /**
     * @param int $start for model, from which position start
     * @param int $count for model, how many users show
     * @return array with users where keys in this array is login of user
     */
    public static function showUsers($start = 0, $count = 5)
    {
        $users = db::getInstance()->Select("select * from users limit $start, $count");
        return Functions::mapArray('login', $users);
    }
}

