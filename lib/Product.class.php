<?php

class Product
{
    /**
     * @param $id id product
     * @return array with product,
     * when user authenticated method check's database it this product in table basket,
     * otherwise check's with $_SESSION['basket']
     */
    public static function getProduct($id)
    {
        $product = db::getInstance()->Select("select * from products where id = :id", ['id' => $id]);

        if (Auth::getIdUser()) {

            $sql = "select products.*, basket.count from products
                        left join basket on basket.ID_UUID = products.ID_UUID
                        where products.id = :id_product and basket.id_user = :id_user";

            $productInBasket = db::getInstance()->Select($sql, ['id_product' => $id, 'id_user' => Auth::getIdUser()]);

            return $productInBasket ? $productInBasket : $product;

        } else {

            $uid = $product[0]['ID_UUID'];

            if (array_key_exists($uid, $_SESSION['basket'])) {
                $product[0]['count'] = $_SESSION['basket'][$uid]['count'];
            }
        }
        return $product;
    }

    /**
     * @param $idCategory id category
     * @return array of products in category where $idCategory
     */
    public static function getProducts($idCategory)
    {
        $sql = "select products.*, categories.name as name_category from products
                inner join categories on categories.id_category = products.id_category
                where products.id_category = 
                (select categories.id_category from categories 
                inner join pages on categories.id_pages = pages.id 
                where pages.url = :id)";

        return db::getInstance()->Select($sql, ['id' => $idCategory]);
    }

    /**
     * @param $category is url from table pages, we use it for getting id from table pages and then this id we use for
     * getting parent_id from categories where id_pages from categories = id from pages
     * @return array with parent category, 1 row
     */
    public static function getParentCategory($category)
    {

        $sql = "select * from categories 
                      inner join pages on categories.id_pages = pages.id 
                      where id_pages = (select id from pages where url = :url)";
        return db::getInstance()->SelectRow($sql, ['url' => $category]);
    }

    /**
     * @param int $quantity how many products show
     * @param string $sort, to sort descending or ascending order from sql database [optional]
     * @return array with popular products
     */
    public static function topProduct($quantity = 6, $sort = 'desc')
    {
        return db::getInstance()->Select("select * from products order by view $sort, date desc limit $quantity;");
    }

    /**
     * @param int $quantity, how many products to show
     * @param string $sort, to sort descending or ascending order from sql database [optional]
     * @return array with last added products
     */
    public static function newProduct($quantity = 6, $sort = 'desc')
    {
        return db::getInstance()->Select("select * from products order by date $sort limit $quantity;");
    }

    /**
     * @param int $quantity, how many products to show
     * @param string $sort, to sort descending or ascending order from sql database [optional]
     * @return array with products for sale
     */
    public static function statusProduct($quantity = 6, $sort = 'desc')
    {
        return db::getInstance()->Select("select * from products where salePrice != 0 limit $quantity;");
    }

    /**
     * @param $uid is ID_UUID from table products
     * @return true if information about product updated successfully
     */
    public static function editProduct($uid)
    {
        $imgProduct = db::getInstance()->SelectRow("select img from products where ID_UUID = :uid", ['uid' => $uid])['img'];
        if (!$imgProduct) {
            $imgProduct = null;
        }

        $image = Image::downloadImageUser();

        if ($image['image_name']) {
            $img = $image['image_name'];
        } else {
            $img = $imgProduct;
        }

        if ($_POST['status'] == 'on_sale') {
            if ($_POST['sale'] == 0) {
                $salePrice = null;
                $status = 0;
            } else {
                $salePrice = $_POST['price'] - ($_POST['sale'] / 100 * $_POST['price']);
                $status = 1;
            }
        } else {
            $salePrice = null;
            $status = 0;
        }

        if (Auth::isAdmin()) {
            $sql['sql'] = "update products set name = :name_product, img = :img, price = :price, id_category = :id_cat, 
                            status = :status, salePrice = :sale_price, date = :date_update, description = :description,
                            short_description = :short_desc where ID_UUID = :uid";
            $sql['param'] = [
                'name_product' => $_POST['name'],
                'img' => $img,
                'price' => $_POST['price'],
                'id_cat' => $_POST['category'],
                'status' => $status,
                'sale_price' => $salePrice,
                'date_update' => date('Y-m-d'),
                'description' => $_POST['description'],
                'short_desc' => $_POST['short_description'],
                'uid' => $uid
            ];
            if (db::getInstance()->Query($sql['sql'], $sql['param'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @return true if admin added new product
     */
    public static function addProduct()
    {

        if (Auth::isAdmin()) {
            $image = Image::downloadImageUser();
            $idCategory = $_POST['category'];
            $price = $_POST['price'];
            $sale = $_POST['sale'];
            if ($image['image_name']) {
                $nameImg = $image['image_name'];
            }

            if ($_POST['status'] == 'on_sale') {
                $salePrice = $price - ($sale / 100 * $price);
                $status = 1;
            } else {
                $salePrice = null;
                $status = 0;
            }

            $sql['sql'] = "insert into products (name, img, price, id_category, status, view, salePrice, date, 
                            description, short_description, ID_UUID) values (:name_product, :img, :price, :id_category, 
                            :status, :view_product, :salePrice, :date_added, :description, :short_description, :ID_UUID)";
            $sql['param'] = [
                'name_product' => $_POST['name'],
                'img' => $nameImg,
                'price' => $price,
                'id_category' => $idCategory,
                'status' => $status,
                'view_product' => 0,
                'salePrice' => $salePrice,
                'date_added' => date('Y-m-d'),
                'description' => $_POST['description'],
                'short_description' => $_POST['short_description'],
                'ID_UUID' => $_POST['uid']
            ];
            if (db::getInstance()->Query($sql['sql'], $sql['param'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * @param $action $_GET['action'] [optional]
     * @return array of categories
     */
    public static function getCategories($action = null)
    {
        if (isset($action)) {

            $sql = "select categories.*, pages.url from categories 
                      inner join pages on categories.id_pages = pages.id
                      where pages.url = :category or categories.parent_id = 
                      (select id_category from categories
                        inner join pages on categories.id_pages = pages.id
                         where pages.url = :category)";

            $categories = db::getInstance()->Select($sql, ['category' => $action]);
        } else {
            $sql = "select categories.*, pages.url from categories 
                      inner join pages on categories.id_pages = pages.id";
            $categories = db::getInstance()->Select($sql);
        }

        foreach ($categories as $key => $value) {
            if ($value['parent_id'] == 0) $result[] = $value;
            foreach ($categories as $k => $val) {
                if ($val['parent_id'] == $value['id_category']) $result[$key]['child'][] = $val;
            }
        }
        return $result;
    }
}