<?php

class Valium
{
	private $regex;
	private $functions;

	public $throw_404_on_error;

	function __construct()
	{
		$this->regex = array();
		$this->functions = array();
		$this->throw_404_on_error = false;
	}

	public function error($number)
	{
		if($number == 404)
		{
			header("HTTP/1.0 404 Not Found");

			print("<!DOCTYPE HTML PUBLIC \"-//IETF//DTD HTML 2.0//EN\">\n");
			print("<html><head>\n");
			print("<title>404 Not Found</title>\n");
			print("</head><body>\n");
			print("</body></html>");
			exit();
		}

		header("HTTP/1.0 " . $number);
		exit();
	}

	public function route($regex, $function)
	{
		$i = count($this->regex);
		$m = @preg_match($regex, "blahblahblah"); // Check the regex is valid
		if($m === false)
		{
			$error = error_get_last();
			throw new Exception($error['message']);
		}

		$this->regex[$i] = $regex;
		$this->functions[$i] = $function;
	}

	public function run()
	{
		$uri = preg_replace("/([^\\?]*)\\?(.*)/", "$1", $_SERVER['REQUEST_URI']);
		$get = $_GET;
		$post = $_POST;
		if(!(is_array($get))) { $get = array(); }
		if(!(is_array($post))) { $post = array(); }

		$c = count($this->regex);
		for($i = 0; $i < $c; $i++)
		{
			$m = array();
			if(preg_match($this->regex[$i], $uri, $m) == 0)
			{
				continue;
			}
			$function = $this->functions[$i];
			$ret = $function($m, $get, $post);
			return($ret);
		}

		if($this->throw_404_on_error)
		{
			$this->error(404);
		}
		return(array());
	}
}
