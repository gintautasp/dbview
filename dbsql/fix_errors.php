<?php

	if ( isset ( $_POST [ 'fix_errors' ] ) && ( $_POST [ 'fix_errors' ] == 'Fiksuoti klaidas' ) ) {
	
		// $status = 'kt1';

		$abo_klaida_sask_nr = array (1001,10,76) ;	

		$qw_fix =
				"
			UPDATE 
				`dat_einvoices`
			SET
				`abolished`=" . $_POST [ 'abo' ] . "
				" . ( in_array ( $_POST [ 'abo' ], $abo_klaida_sask_nr ) ? '' : ', `repeat`=1' ) . "
			WHERE
				`dat_einvoices`.`id`IN(" . $_POST [ 'saskaitos' ] . " )
				";
		$db -> perform_query ( $qw_fix );

	}