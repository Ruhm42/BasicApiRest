<?php

require_once 'Retcode.php';

abstract class Rest extends Retcode
{

	/*
	**	This rest abstract class doesn't support verbs, only simple endpoints.
	**	You can't request /user/42/order but, /order?user_id=42 will work
	*/

	protected $method = 0;
	protected $target = 0;
	protected $args = Array();

	public function __construct($request) {
		header("Access-Control-Allow-Orgin: *");
		header("Access-Control-Allow-Methods: *");
		header("Content-Type: application/json");
		$this->args = explode('/', rtrim($request, '/'));
		$this->target = array_shift($this->args);
		$this->method = $_SERVER['REQUEST_METHOD'];
		if ($this->method == 'POST'
			&& array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)
			&& $_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE')
				$this->method = 'DELETE';
		$this->request = $this->_cleanInputs($_POST);
		$this->request = $this->_cleanInputs($_GET);
	}

	public function processAPI()
	{
		if (method_exists($this, $this->target))
			return $this->_response($this->{$this->target}($this->args));
		return $this->generateError(404, "No endpoint: $this->target");
	}

	private function _response($data)
	{
		$this->httpStatus();
		return json_encode($data);
	}
	
	private function _cleanInputs($data)
	{
		$clean_input = Array();
		if (is_array($data))
			foreach ($data as $k => $v)
				$clean_input[$k] = $this->_cleanInputs($v);
		else
			$clean_input = trim(strtolower(strip_tags($data)));
		return $clean_input;
	}
}

?>