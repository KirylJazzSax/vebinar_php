<?php

class Model
{
    public $view = 'index';
    public $title;
    public $model;
    public $method;
    public $out_row;
    public $template;
    public $current_record;
    public $data;

    function __construct()
    {	
        $this->title = Config::get('sitename');
        $this->data = $_GET['id'];
    }

//    public function index($data = null, $deep = 0)
//	{
//
//    }

	public function __call($methodName, $args) 
	{
    	header("Location: Config::get('domain')/page404");
  	}
	

}