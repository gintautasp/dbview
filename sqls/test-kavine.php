<?php

	$params = array( 
	
		// 'bankas' =>  array ( 'current' => _cfihod ( $_GET, 'bankas', 'nord' ), 'values' => array ( 'nord' => 'dnb', 'swed' => 'swed', 'seb' => 'seb', 'siaub' => 'šiaulų' ) )
		// , 'period' => _cfihod ( $_GET, 'period', date ( "Ym" ) )
	); 	

	$query =
	
			"
		SELECT SQL_CALC_FOUND_ROWS 
			* 			
		FROM 
			`uzsakymai` 
		WHERE
			1
				";
			
	$names_fields = array ( 
	);
		
	$calcs = array ();
		
	$calc_params = array();			