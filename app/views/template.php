<!doctype html>
<html lang="en">
<head>
<!-- title & meta -->
<title><?=$site_title;?></title>
<meta charset="UTF-8">

<!-- stylesheets -->
<link rel="stylesheet" href="<?=base_url('/css/layout.css');?>" type="text/css" />
<?= $_styles ?>

<script src="<?=base_url('js/jquery-1.6.4.min.js')?>"></script>
<script src="<?=base_url('js/jquery.tools.min.js')?>"></script>
<?= $_scripts ?>
</head>
<body>

<div id="wrapper">
	<header id="page_header">
		<h1><?=$site_title;?></h1>
		<nav>
			<ul>
				<li><a href="<?=site_url('/');?>">Homepage</a></li>
			</ul>
		</nav>
	</header>
	   
	<section id="content">
		<?=$content;?>
	</section>
	
	<footer id="page_footer">
		<p>&copy; Copyright <?=date('Y');?> <?=$app_name;?></p>
	</footer>

</div><!-- end #wrapper -->

</body>
</html>