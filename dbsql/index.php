<!DOCTYPE html>
<?php
	$status = 'kt0';
	include 'main.php';
?>
<html>
<head>
	<title>užklausų rezultatų peržiūra</title>
	<meta charset="utf-8">
	<meta name="robots" content="index, follow">
	<meta name="description" content="užklausų rezultatų peržiūra">
	<meta name="keywords" content="projektavimas, dokumentavimas, projekto dokumentacija">
	<style type="text/css">
		.main {
			display: inline-block;
			background-color: Bisque;
		}
		.minimized {
			border: 1px solid black;
			padding: 2px;
		}
		.maximized {
			display: none;
		}
<?php
	// css includes
	include 'style.css.php';
	include MAIN_DIR . 'css/debug.css';
?>
	</style>
	<script src="jquery-1.8.3.js"></script>
	<script type="text/javascript">
		$(document).ready ( function() {
<?php
		// js includes
?>
		});
	</script>
</head>
<body>

	<div id="page_main">
		<div class="main">
<?php
	echo _cfihod ( $qvw, 'params',  '' );
	
	echo $qw_sub_form;
	
	echo _cfihod ( $qvw, 'table',  '' );
?>	
		</div>
	</div>
	<footer>
<?php	
	db_errors_log_debug_info ( SHOW_DEBUG_INFO, 'LOCAL' );	
?>
	</footer>
</body>
</html>