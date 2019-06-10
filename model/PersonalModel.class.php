<?php 

class PersonalModel extends Model
{
	
	public $view = 'personal';
	public $title;
	
	public function __construct()
	{
		parent::__construct();
		$this->title .= "Личный кабинет";
	}

	function index($user = NULL, $deep = 0)
	{
        return [
            'user' => $user,
        ];
	}

	function edit($user) {

        return [
            'user' => $user,
        ];
    }
}