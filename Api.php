<?php

require_once 'Database.php';

class Api extends Database
{

	public function __construct($request) {
		parent::__construct($request);

	}

	/*
	**	Endpoints.
	**	You have to call method caller to execute generic method (GET, POST, DELETE)
	**	Or you can ignore them and implements specific behaviours.
	*/

	protected function user()
	{
		return $this->methodCaller();
	}

	protected function task()
	{
		return $this->methodCaller();
	}
}

 ?>