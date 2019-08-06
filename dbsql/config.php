<?php

	define ( 'LOG_ALL_QUERIES', false);
	define ( 'SHOW_DEBUG_INFO', 1 );
	define ( 'SHOW_EXT_DEBUG_INFO', 0);
	define ( 'WORK_MODE',  'ONLINE' );
	
	error_reporting (E_ALL | E_STRICT | E_WARNING | E_PARSE | E_NOTICE ); // error_reporting ( E_ALL | E_STRICT );
	
	ini_set ( 'display_errors', '1' );

	$clients_dan_config = array (
	
		  'commons_dir' => MAIN_DIR . '/../pr_cm/'	
		, 'users_data_dir' => MAIN_DIR . '../_files/'
		
		, 'server_dir' => '/' 		
		, 'SERVER_NAME' => 'http://' . $_SERVER [ 'SERVER_NAME' ] . '/'
		
		, 'db_name' => 'kavine'
		, 'db_user' => 'root'
		, 'db_password' => ''
		
		, 'default_language' => 'lt'
		
		, 'log_file' => MAIN_DIR  . '../logs.txt'
	);
	
	$real_conf = $clients_dan_config;
	
	require $real_conf [ 'commons_dir' ] . 'libs/mle.php';	
	require $real_conf [ 'commons_dir' ] . 'class/debug.php';
	
	require MAIN_DIR . 'functions.php';
	set_error_handler ( 'errors_handler' );

	require $real_conf [ 'commons_dir' ] . 'class/languages.php';
	
	$lang -> get_from_file ( $real_conf [ 'default_language' ], $real_conf [ 'commons_dir' ] . 'lang/db_mysql_user1.php', 'translations' );
	
	require $real_conf [ 'commons_dir' ] . 'class/db_mysql_user1.php';
	require $real_conf [ 'commons_dir' ] . 'class/db_4_www.php';
	
	$db = new db_4_www ( $real_conf [ 'db_name' ], $real_conf [ 'db_user' ], $real_conf [ 'db_password' ], LOG_ALL_QUERIES );	
	require $real_conf [ 'commons_dir' ] . 'libs/db_fetch.php';	
