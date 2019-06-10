<?php

class IndexModel extends Model
{
    public $view = 'index';

    function __construct()
    {
        parent::__construct();
    }

    public function index($data = null, $deep = 0) {}
}