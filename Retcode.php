<?php

abstract class Retcode
{
	protected $ret_code = 200;
	private $_dictionnary = array(  
		200 => 'OK',
		201 => 'Content created',
		204 => 'No Content',
		400 => 'Bad Request',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		500 => 'Internal Server Error',
	); 

	private function _getStatus()
	{
		if (array_key_exists($this->ret_code, $this->_dictionnary))
			return $this->_dictionnary[$this->ret_code];
		else 
			return $this->_dictionnary[500]; 
	}

	public function httpStatus()
	{
		header("HTTP/1.1 " . $this->ret_code . " " . $this->_getStatus());
	}

	public function generateError($retcode, $content = '')
	{
		$this->ret_code = $retcode; 
		return array('error' => $this->_getStatus(), 'error_description' => $content);
	}
}


?>