<?php 

class Instagram 
{
    /**
     * We use this method just to show pictures in the templates
     * @param int $quantity amount of products for select
     * @return products from table products
     */
	public static function Insta($quantity = 6)
	{
		return db::getInstance()->Select("select img from products limit $quantity;");
	}
	
}