<?php

class Bread
{
    /**
     * @param $url requested URL
     * @return array of breadcrumbs
     */
    public static function BreadCrumbs($url)
    {
        $breadCrumbs = [];
        $page = Config::get('domain');
        // make array of breadcrumbs when $_GET['page'] == good
        if ($_GET['page'] == 'good') {
            //id product
            $id = $_GET['id'];
            // get name and url category of product from table pages
            $sqlCategory = "select * from pages
                    where id = (select id_pages from categories 
                                where id_category = (select id_category from products 
                                where products.id = $id))";
            $category = db::getInstance()->SelectRow($sqlCategory);
            // id of parent category
            $parent = $category['parent_id'];
            // name category of product
            $name = $category['name'];
            // url for category
            $urlCategory = $category['url'];

            // get all parent categories
            $sql = "select * from pages where parent_id < $parent  order by parent_id desc";
            $result = db::getInstance()->Select($sql);

            foreach ($result as $key => $item) {
                if ($parent != $item['id']) {
                    unset($result[$key]);
                } else {
                    $parent = $item['parent_id'];
                }
            }

            // make breadcrumbs
            foreach (array_reverse($result) as $key => $item) {
                // first value in array would be Main Page
                if ($item['url'] == 'index') {
                    $breadCrumbs[$page] = $item['name'];
                } else {
                    $page .= '/' . $item['url'];
                    $breadCrumbs[$page] = $item['name'];
                }
            }
            // add to the end of breadcrumbs link category of product
            $urlCategory = end(array_keys($breadCrumbs)) . '/' . $urlCategory;
            $breadCrumbs = array_merge($breadCrumbs, [$urlCategory => $name]);

        } else {
            // make breadcrumbs for all pages except of 'good'
            for ($i = 1; $i < count($url); $i++) {
                // get name of the page from database if exist
                $urlPage = db::getInstance()->SelectRow("select name from pages where url = :url", ['url' => $url[$i]])['name'];
                $page .= '/' . $url[$i];
                $breadCrumbs[$page] = $urlPage;
            }
            // add Main Page to breadcrumbs as a first element
            $urlPage = db::getInstance()->SelectRow("select name from pages where url = :url", ['url' => 'index'])['name'];
            $mainPage = [Config::get('domain') => $urlPage];
            $breadCrumbs = array_merge($mainPage, $breadCrumbs);
        }
        return $breadCrumbs;
    }
}