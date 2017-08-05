<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('json'))
{
	function json($var)
	{
		echo json_encode($var);

		return TRUE;
	}
}
