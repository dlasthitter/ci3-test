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

if ( ! function_exists('render'))
{
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

if ( ! function_exists('getOrders'))
{
	function getOrders($ci)
	{
		$orders = $ci->session->userdata("orders");
		if (!isset($orders)) $orders = [];

		return $orders;
	}
}

if ( ! function_exists('calculateTotal'))
{
	function calculateTotal($orders)
	{
		$total = 0;

		foreach($orders['items'] as $order) {
			$total += $order['item']->price * $order['qty'];
		}

		return $total;
	}
}
