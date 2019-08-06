<?php

	define ( 'DIR_UP',  __DIR__ . '/../' );
	
	define ( 'MAIN_DIR', __DIR__ . '/'   );

	// echo MAIN_DIR;
	
	include MAIN_DIR . '/config.php';
	
	$link_back = '';

	if ( isset ( $_GET [ 'vwq' ] ) ) {
	
		$vwq = $_GET [ 'vwq' ];

		include 'viewByQuery.class.php';

		include DIR_UP . 'sqls/' . $vwq . '.php';
	
		$vbq = new viewByQuery (
	
			$query, $names_fields, $params, $calcs, $calc_params, $link_back
		);
	
		$qvw = array ( 
			
			'params' => $vbq -> params_row()
			, 'table' => $vbq->take()
		);
	}