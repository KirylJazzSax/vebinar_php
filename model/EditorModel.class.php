<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 02.05.2019
 * Time: 13:23
 */

class EditorModel extends Model
{
    public $view = 'editor';
    public $title;

    function __construct()
    {
        parent::__construct();
        $this->title .= 'Для редактора!';
        $this->model = __CLASS__;
        $this->template = 'articles';

    }

    function index($data = null, $start = 0, $count = 2)
    {
        $currentRecord = $start + $count;

        $countArticles = db::getInstance()->SelectRow("select count(*) as count_articles from articles")['count_articles'];

        $outRow = $countArticles <= $currentRecord ? false : true;

        $this->method = __FUNCTION__;
        $this->out_row = $outRow;
        $this->current_record = $currentRecord;

        if (Auth::isAdmin()) {
            $articles = Articles::getArticles($start, $count);
        } else {
            $articles = Articles::getArticles($start, $count, Auth::getIdUser());
        }

        Debug::Deb($articles);
        return $articles;
    }

    function editor($article)
    {
        return $article;
    }

    function id($data, $start = 0, $count = 5)
    {
        $count = db::getInstance()->SelectRow("select count(*) as count_articles from articles")['count_articles'];

        return Articles::getArticles($start, $count, $data);
    }

    function add()
    {
    }

    function edit()
    {
        return Articles::showArticle($_GET['id']);
    }

}