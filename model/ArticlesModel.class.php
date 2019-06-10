<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 28.04.2019
 * Time: 9:59
 */

class ArticlesModel extends Model
{
    public $view = 'articles';
    public $title;

    function __construct()
    {
        parent::__construct();
        $this->title .= 'Статьи';
        $this->model = __CLASS__;
    }

    function index($data = null, $start = 0, $count = 2)
    {
        $currentRecord = $start + $count;

        $countArticles = db::getInstance()->SelectRow("select count(*) as count_articles from articles")['count_articles'];

        $outRow = $countArticles <= $currentRecord ? false : true;

        $this->method = __FUNCTION__;
        $this->out_row = $outRow;
        $this->template = 'articles';
        $this->current_record = $currentRecord;

        return Articles::getArticles($start, $count);
    }

    function articles($idArticle = null)
    {
        return Articles::showArticle($idArticle);
    }
}