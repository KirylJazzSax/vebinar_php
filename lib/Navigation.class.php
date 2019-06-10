<?php

class Navigation
{
    /**
     * static method for set menu on the website
     * @return array with pages and urls for this pages
     */
    public static function navigationMenu()
    {
        // get url for main pages
        $menu = db::getInstance()->Select("select * from pages where parent_id = :parent", ['parent' => 1]);
        $page = mb_strtolower($_GET['page']);

        // get url for catalog and sub_catalog
        if ($page == 'catalog' || $page == 'good') {
            $page = 'catalog';
            $sql = "select * from pages
                    where parent_id = (select id from pages where url = :url)";
            $menu = db::getInstance()->Select($sql, ['url' => $page]);
        }

        // don't show url for pages which can use only admin
        if (!Auth::logIn()) {
            foreach ($menu as $key => $value) {
                if ($value['url'] == 'admin' || $value['url'] == 'user') {
                    unset($menu[$key]);
                }
            }
        }
        return $menu;
    }
}