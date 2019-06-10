<?php
class RegisterModel extends Model
{
	public $view = 'register';
	public $title;
	
	public function __construct()
	{
		parent::__construct();
		$this->title .= "Регистрация пользователя";
	}

	function index() {}
}

