<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.05.2019
 * Time: 13:23
 */

class EditorController extends Controller
{
    public $view = 'editor';


    public function index()
    {
        if (!Auth::isEditor() && !Auth::isAdmin()) return self::not_editor();

        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();

    }

    public function editor()
    {
        if (!Auth::isEditor() && !Auth::isAdmin()) return self::not_editor();

        $article = Articles::showArticle($_GET['id']);

        if ($article) {
            $this->view .= "/" . __FUNCTION__ . '.php';
            echo $this->controller_view($article);
        } else {
            $msg = 'Не удалось найти статью!';
            self::info($msg);
        }
    }

    public function id()
    {
        $idEditor = $_GET['id'];

        if (Auth::isAdmin()) {
            if (empty(Articles::getArticles(0, 5, $idEditor))) {
                $msg = 'Редактора такого нет или у него нет пока еще статей!';
                return self::info($msg);
            } else {
                $this->view .= "/" . __FUNCTION__ . '.php';
                echo $this->controller_view($idEditor);
            }
        } else {
            self::not_admin();
        }
    }

    public function add()
    {
        if (!Auth::isEditor() && !Auth::isAdmin()) return self::not_editor();

        $idEditor = Auth::getIdUser();

        if ($_POST['add_article']) {
            if (Articles::addArticle($idEditor)) {
                $msg = 'Вы добавили статью!';
            } else {
                $msg = 'Простите что-то пошло не так!';
            }
            return self::info($msg);
        }

        $this->view .= "/" . __FUNCTION__ . '.php';
        echo $this->controller_view();
    }

    public function edit()
    {
        if (!Auth::isAdmin() && !Auth::isEditor()) return self::not_editor();

        $article = Articles::showArticle($_GET['id'])[0];
        $editor = $article['id_editor'];

        if (Auth::isAdmin() || Auth::getIdUser() == $editor) {
            if ($article) {
                if ($_POST['edit_article']) {
                    if (Articles::editArticle($_GET['id'])) {
                        $msg = 'Вы изменили статью!';
                    } else {
                        $msg = 'Простите что-то пошло не так!';
                    }

                    return self::info($msg);
                }
            } else {

                $msg = 'Не удалось найти статью!';
                return self::info($msg);
            }
        } else {

            $msg = 'У вас нет права изменять эту статью!';
            return self::info($msg);
        }

        self::add();
    }
}