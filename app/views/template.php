<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="en"> <!--<![endif]-->
<head>
	<!-- Title &amp; Meta -->
	<title><?=$site_title;?></title>
	<meta charset="utf-8">
	<meta name="apple-mobile-web-app-status-bar-style" content="black"> 
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
	<meta name="author" content="Pete Hawkins @peteyhawkins">
	<meta name="description" content="">

	<!-- Stylesheets -->
	<link rel="stylesheet" href="<?=base_url('stylesheets/base.css');?>">
	<link rel="stylesheet" href="<?=base_url('stylesheets/skeleton.css');?>">
	<link rel="stylesheet" href="<?=base_url('stylesheets/layout.css');?>">
	<?= $_styles ?>

	<!-- Favicons -->
	<link rel="shortcut icon" href="<?=base_url('images/favicon.ico');?>">
	<link rel="apple-touch-icon" href="<?=base_url('images/apple-touch-icon.png');?>">
	<link rel="apple-touch-icon" sizes="72x72" href="<?=base_url('images/apple-touch-icon-72x72.png');?>">
	<link rel="apple-touch-icon" sizes="114x114" href="<?=base_url('images/apple-touch-icon-114x114.png');?>">
	
	<!-- IE Fallbacks -->
	<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<script src="<?=base_url('javascripts/css3-mediaqueries.js');?>"></script>
	<![endif]-->

</head>
<body>

<div class="container">
	<header class="sixteen columns header">
		<h1><?=$site_title;?> <small>v0.1</small></h1>
	</header>
	   
	<section id="content">
		<?php if ($this->session->flashdata('success')): ?>
			<p class="info success"><?=$this->session->flashdata('success')?></p>
		<?php endif; ?>
		<?php if ($this->session->flashdata('notice')): ?>
			<p class="info notice"><?=$this->session->flashdata('notice')?></p>
		<?php endif; ?>
		<?php if ($this->session->flashdata('error')): ?>
			<p class="info error"><?=$this->session->flashdata('error')?></p>
		<?php endif; ?>
		<?=$content;?>
	</section><!-- end #content -->
	
	<footer class="sixteen columns footer">
		<p>&copy; Copyright <?=date('Y');?> <?=$app_name;?></p>
	</footer>
</div><!-- end .container -->

	<!-- JS
	================================================== -->
	<script src="<?=base_url('javascripts/jquery-1.7.1.min.js');?>"></script>
	<script src="<?=base_url('javascripts/json2.js');?>"></script>
	<script src="<?=base_url('javascripts/underscore-min.js');?>"></script>
	<script src="<?=base_url('javascripts/backbone-min.js');?>"></script>
	<script src="<?=base_url('javascripts/handlebars-1.0.0.beta.6.js');?>"></script>
	<?= $_scripts ?>
</body>
</html>