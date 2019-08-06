<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title><?= $html -> title ?></title>
	<meta name="keywords" content="<?= $html -> keywords ?>">
	<meta name="description" content="<?= $html -> description ?>">
<?php
		while ( $html -> css_ex_files -> takeNext() ) {
?>		
			<link rel="stylesheet" href="<?= $html -> css_ex_files -> piece ?>">
<?php		
		}
?>
<style>
<?php
		while ( $html -> css_in_files -> takeNext() ) {
		
			include $html -> css_in_files -> piece;		
		}
?>
</style>
<?php		
		while ( $html -> js_ex_files -> takeNext() ) {
?>
		<script src="<?= $html -> js_ex_files -> piece ?>"></script>
<?php		
		}
?>
<script>
	$(document).ready( function() {
<?php		
		while ( $html -> js_in_files -> takeNext() ) {
		
			include $html -> js_in_files -> piece;
		}
		while ( $html -> js_scripts -> takeNext() ) {
		
			echo $html -> js_scripts -> piece;
		}
?>
	});
</script>
</head>
<body>
<?php
	include $html -> tpl_content;
	
	$log_main ->see();
?>
</body>
</html>
