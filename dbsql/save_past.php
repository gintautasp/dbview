<?php

	if ( isset ( $_POST [ 'save_notes' ] ) && ( $_POST [ 'save_notes' ] == 'Saugoti pastabas' ) && isset ( $_POST [ 'i_n' ] ) ) {
	
		// $status = 'kt1';
	
		$lst_upd = array ();
		
		foreach ( $_POST [ 'i_n' ] as $i => $n ) {
		
			if ( $n != '' ) {
			
				$lst_upd[] = substr ( $i, 1 ) . ", '" . date ("Y-m-d") . "', '" .  mysql_real_escape_string ( $n ) . "'";
			}
		}
		
		if ( $lst_upd ) 
		
		$qw_ins =
				"
			INSERT INTO  `inf_nariai_kontaktai` ( `id_nariai`, `data`, `pastabos` )
			VALUES
				(" . implode ( '), (', $lst_upd ) . ")
			ON DUPLICATE KEY UPDATE
				`pastabos`=VALUES(`pastabos`)
				";
		$db -> perform_query ( $qw_ins );
	
	}