<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Shop</title>
	<base href="<?=$this->config->config['base_url']?>" />
	
	<link href="assets/font-awesome-4.7.0/css/font-awesome.css" rel="stylesheet" />	
	<link href="assets/tether-1.3.3/dist/css/tether.css" rel="stylesheet" />
	<link href="assets/bootstrap-4.0.0/css/bootstrap.css" rel="stylesheet" />

	<script type="text/javascript" src="assets/js/jquery.js"></script>
    <script src="assets/tether-1.3.3/dist/js/tether.js"></script>
	<script type="text/javascript" src="assets/bootstrap-4.0.0/js/bootstrap.js"></script>
	<script type="text/javascript" src="assets/js/helper.js"></script>
	<script type="text/javascript" src="assets/js/shop.js"></script>

	<script type="text/javascript">
		var app = {
			urlsite : '<?=$this->config->item('base_url')?>'
		};

	</script>
</head>
<body>
	<div id="container">
		<div id="body">

