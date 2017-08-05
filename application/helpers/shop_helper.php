<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if ( ! function_exists('json'))
{
	function json($var)
	{
		echo json_encode($var);

		return TRUE;
	}

	function render($ci, $template = [], $data = [])
	{
		$ci->load->view('header', $data);
		
		if (is_array($template)) {
			foreach($template as $tpl) {
				$ci->load->view($tpl, $data);
			}
		} elseif(is_string($template)) {
			$ci->load->view($template, $data);		
		}

		$ci->load->view('footer', $data);
	}
}
