<?php

	$params = array( 
	
		 'busena' =>  array ( 
		 
			'current' => _cfihod ( $_GET, 'busena', 'visos' )
			
			, 'values' => array ( 
			
				'visos' => 'visos'
				, 'ivykdytas' => 'įvykdytas'
				, 'anuliuotas' => 'anuliuotas'
				, 'uzsakyta' => 'užsakytas' 
			) 
		)
		
		, 'diena' => _cfihod ( $_GET, 'diena', date ( "Y-m-d" ) )
	); 	

	$query =
	
			"
		SELECT SQL_CALC_FOUND_ROWS 
			`uzsakymai`.`pav` AS `uzsakymas`
			, `uzsakymai`.`id` AS `id_uzsak`
			, `uzsakymai`.`trukme_ruosimo` AS `ruosim`
			, `uzsakymai`.`trukme_kaitinimo` AS `kaitinsim`
			, `busena`
			, `laikas_uzsakymo` AS `laikas_uzsak`
			, `laikas_patekimo` AS `laikas_pateik`
			, `uzsakymai`.`klientas` AS `klientas`
			, `uzsakymai`.`id_patiekalo` AS `id_pat`
			, `uzsakymai`.`kaina` AS `kaina_uzsak`
			, `patiekalai`.`id` AS `pat_id`
			, `patiekalai`.`pav` AS `patiekalas`
			, `patiekalai`.`trukme_ruosimo` AS `ruosti`
			, `patiekalai`.`trukme_kaitinimo` AS `kaitinti`
			, `patiekalai`.`kaina` AS `kaina_patiek`
		FROM 
			`uzsakymai` 
		LEFT JOIN 
			`patiekalai` ON (
			
				`uzsakymai`.`id_patiekalo`=`patiekalai`.`id`
			)
		WHERE
				1
			AND
			    '" . $params [ 'diena' ] ."' = SUBSTRING( `uzsakymai`.`laikas_uzsakymo`,1, 10 )
			AND
			   " . ( 
					$params [ 'busena'  ] [ 'current' ] == 'visos'
				?
					"1"
				:
					" '" . $params [ 'busena'  ] [ 'current' ] . "'=`uzsakymai`.`busena`"
			) . "
				";
			
	$names_fields = array ( 
	
		'uzsakymas' => 'užsakymas'
		,  'id_uzsak' => 'id<br>užsak.'
		, 'ruosim' => 'ruošim'
		, 'busena' => 'būsena'
		, 'laikas_uzsak' => 'laikas<br>užsak.'
		, 'laikas_pateik' => 'laikas<br>pateik.'

		, 'id_pat' => 'id<br>tpat.'
		, 'kaina_uzsak' => 'kaina<br>užsak.'
		, 'pat_id' => 'pat.<br>id'
		, 'patiekalas' => 'patiekalas'
		, 'ruosti' => 'ruošti'
	);
		
	$calcs = array ();
		
	$calc_params = array();			