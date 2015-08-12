<?php

require_once 'Rest.php';

class Database extends Rest
{

	/*
	**	This class assume database tables names are equal with endpoints.
	**	There is no filters, pagination
	*/

	private $db_user = 'root';
	private $db_pass = 'root';

	public function __construct($request) {
		parent::__construct($request);

	}

	private function linkDb()
	{
		$link = new PDO('mysql:host=localhost;dbname=test', $this->db_user, $this->db_pass);
		$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $link;
	}

	/*
	**	create a dynamic sql query with $_GET or $_POST arguments
	*/

	private function get_db()
	{
		$pdo = $this->linkDb();
		$full_array = array();
		if (count($this->args) >= 1)
			$full_array[$this->target . "_id"] = $this->args[0];
		$full_array = array_merge($_GET, $full_array);
		unset($full_array['request']);
		$params = implode(" = ? AND ", array_keys($full_array));
		$str = "SELECT * FROM $this->target";
		if (count($full_array) > 0)
			$str .= " WHERE $params = ?";
		$statement = $pdo->prepare($str);
		$statement->execute(array_values($full_array));
		$ret = $statement->fetchAll(PDO::FETCH_ASSOC);
		if (count($ret) <= 0)
			return $this->generateError(404, "The $this->target doesn't exist.");
		return $ret;
	}


	private function post_db()
	{
		$pdo = $this->linkDb();
		$params = implode(", ", array_keys($_POST));
		$fill = implode(',', array_fill(0, count($_POST), '?'));
		$str = "INSERT INTO $this->target ($params) VALUES ($fill)";
		$statement = $pdo->prepare($str);
		$this->ret_code = 201;
		return $statement->execute(array_values($_POST));
	}

	/*
	**	delete is working only with id. No security check!
	*/

	private function delete_db()
	{
		if (count($this->args) < 1)
			return $this->generateError(404, "The DELETE method needs a target id.");
		$pdo = $this->linkDb();
		$req = "DELETE FROM $this->target WHERE " . $this->target . "_id = " . $this->args[0];
		$res = $pdo->query($req);
		$this->ret_code = 204;
		if (is_a($res, 'PDOStatement'))
			return $res->fetchAll();
		return $res;
	}

	/*
	**	UPDATE method is not present.
	*/

	protected function methodCaller()
	{
		try
		{
			switch($this->method)
			{
				case 'DELETE':
					return $this->delete_db();
				case 'POST':
					return $this->post_db();
				case 'GET':
					return $this->get_db();
				default:
					return $this->generateError(405, "Only DELETE, GET, POST are supported.");
			}
		}
		catch (Exception $e) {return $this->generateError(405, $e->getMessage());}
	}

	/*
	**	Endpoints. You can include code and specific behaviours
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