<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.04.2019
 * Time: 13:44
 */

class Articles
{
    /**
     * @param int $start for model, from which position show articles
     * @param int $count for model, how many articles for show
     * @param $idEditor id editor [optional]
     * @return array whit articles from database, if isset id $idEditor
     * return's array whit articles of editor whit id $idEditor
     */
    public static function getArticles(int $start = 0, int $count = 5, $idEditor = null){

        if (!empty($idEditor)) {

            $sql = "select articles.*, users.name, users.surname from articles 
                    inner join users on articles.id_editor = users.id_user
                    where id_editor = :id_editor
                    order by `date` limit $start, $count";

            return db::getInstance()->Select($sql, ['id_editor' => $idEditor]);
        } else {
            $sql = "select articles.*, users.name, users.surname from articles 
                    inner join users on articles.id_editor = users.id_user
                    order by `date` limit $start, $count";

            return db::getInstance()->Select($sql);
        }
    }

    /**
     * @param $id id article
     * @return array with article
     */
    public static function showArticle($id) {

        $sql = "select * from articles where id_article = :id_article";

        return db::getInstance()->Select($sql, ['id_article' => $id]);
    }

    /**
     * @param $idEditor id editor who added article
     * @return true if editor added article successfully
     */
    public static function addArticle($idEditor) {

        $image = Image::downloadImageUser()['image_name'];
        if (empty($image)) {
            $image = null;
        }

        if (Auth::isEditor() || Auth::isAdmin()) {
            $sql['sql'] = "insert into articles (author, title, description, article, date, img, id_editor, view)
                                  values (:author, :title, :description, :article, :date_add, :img, :id_editor, :view)";
            $sql['param'] = [
                'author' => $_POST['author'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'article' => $_POST['article'],
                'date_add' => date('Y-m-d H-m-s'),
                'img' => $image,
                'id_editor' => $idEditor,
                'view' => 0
            ];

            if (db::getInstance()->Query($sql['sql'], $sql['param'])) {
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * @param $idArticle id article which editor
     * @return true if article with $idArticle updated successfully
     */
    public static function editArticle($idArticle) {

        $image = Image::downloadImageUser()['image_name'];

        if (empty($image)) {
            $sqlImage = "select img from articles where id_article = :id_article";
            $image = db::getInstance()->SelectRow($sqlImage, ['id_article' => $idArticle])['img'];
        }

        if (Auth::isEditor() || Auth::isAdmin()) {
            $sql['sql'] = "update articles set author = :author, title = :title, description = :description, 
                            article = :article, date = :date_update, img = :img, id_editor = :id_editor 
                            where id_article = :id_article";

            $sql['param'] = [
                'author' => $_POST['author'],
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'article' => $_POST['article'],
                'date_update' => date('Y-m-d H-m-s'),
                'img' => $image,
                'id_editor' => $_POST['id_editor'],
                'id_article' => $idArticle
            ];

            if (db::getInstance()->Query($sql['sql'], $sql['param'])) {
                return true;
            }else{
                return false;
            }
        }

    }

    /**
     * @param $idEditor [optional] id editor
     * @return array with articles of editor who now is authenticated,
     * or articles with editor $idEditor
     */
    public static function showEditorArticles($idEditor = null) {

        $sql = "select * from articles where id_editor = :id_editor";

        if (empty($idEditor)) {

            return db::getInstance()->Select($sql, ['id_editor' => Auth::getIdUser()]);
        } else {

            return db::getInstance()->Select($sql, ['id_editor' => $idEditor]);
        }
    }
}