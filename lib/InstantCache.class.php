<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 29.04.2019
 * Time: 13:09
 */

class InstantCache
{
    protected static $instance = null;

    /**
     * @return true if we connected to memcached server
     */
    protected static function connectCache() {
        if (self::$instance) return false;
        $instance = new Memcached();
        $instance->addServer(Config::get('memcached_host'),Config::get('memcached_port'));
        self::$instance = $instance;
    }

    /**
     * @param $key
     * @return mixed
     */
    public static function getKey($key)
    {
        if (!self::$instance)
        {
            self::connectCache();
        }
        return self::$instance->get($key);
    }

    public static function setKey($key, $value, $time = 60)
    {
        if (!self::$instance)
        {
            self::connectCache();
        }
        return self::$instance->set($key, $value, $time);
    }

    public static function deleteKey($key)
    {
        if (!self::$instance)
        {
            self::connectCache();
        }
        return self::$instance->delete($key);
    }
}