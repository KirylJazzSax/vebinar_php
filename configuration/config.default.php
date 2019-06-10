<?php
$config['db_user'] = 'root';
$config['db_password'] = 'vagrant';
$config['db_base'] = 'GU';
$config['db_host'] = '127.0.0.1';
$config['db_charset'] = 'UTF-8';
$config['memcached_host'] = '127.0.0.1';
$config['memcached_port'] = '11211';

$config['path_root'] = __DIR__;
$config['path_public'] = $config['path_root'] . '/../public';
$config['path_model'] = $config['path_root'] . '/../model';
$config['path_controller'] = $config['path_root'] . '/../controller';
$config['path_cache'] = $config['path_root'] . '/../cache';
$config['path_data'] = $config['path_root'] . '/data';
$config['path_fixtures'] = $config['path_data'] . '/fixtures';
$config['path_migrations'] = $config['path_data'] . '/../migrate';
$config['path_commands'] = $config['path_root'] . '/../lib/commands';
$config['path_libs'] = $config['path_root'] . '/../lib';
$config['path_templates'] = $config['path_root'] . '/../templates';

$config['path_logs'] = $config['path_root'] . '/../logs';

$config['sitename'] = 'SAXMUZ | ';
$config['domain'] = 'http://localhost:81';

define('TIME_COOKIE_BASKET', time() + 3600 * 24 * 30 * 12);
define('DELETE_COOKIE_BASKET', time() - 3600 * 24 * 30 * 13);

/*
Конфигурация фотогаллереи
*/
$config['UPLOAD_DIR'] = 'image';
$config['UPLOAD_SMALL_DIR'] = 'image/small/';
$config['img'] = '/img/';

