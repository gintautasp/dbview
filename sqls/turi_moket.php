<?php

	$params = array( 
	
		'bankas' =>  array ( 'current' => _cfihod ( $_GET, 'bankas', 'nord' ), 'values' => array ( 'nord' => 'dnb', 'swed' => 'swed', 'seb' => 'seb', 'siaub' => 'šiaulų' ) )
		, 'period' => _cfihod ( $_GET, 'period', date ( "Ym" ) )
	); 	

	$query =
	
			"
		SELECT SQL_CALC_FOUND_ROWS 
			`dat_nariai`.`iRnr` AS `id`
			, `vardas`	
			, `pavarde`
			, `asmensKodas` AS `ak`
			, `pareigos`	
--			, `telefonai` AS `tel.`	
--			, `eLpastas` AS `e_mail`
			, `dat_activities`.`pony_date_from` AS `narys_nuo`
			, IF(`dat_activities`.`pony_date`='2022-01-01', '&infin;',`dat_activities`.`pony_date`) AS `narys_iki`
			, `dat_units`.`name` AS `padalinys`
			, `dat_member_banks_data`.`sutiKnr` AS `sutik_nr`
			, `dat_pay_sums`.`pay_sum` AS `mok_suma`
			, `dat_pay_sums`.`period_from` AS `mok_suma_nuo`
			, IF(`dat_pay_sums`.`period_to`='2022-01-01', '&infin;',`dat_pay_sums`.`period_to`) AS `mok_suma_iki`
			, `dat_member_banks_data`.`pay_sum_limit` AS `maks_mok_suma`
			, `dat_member_banks_data`.`sasK_korTnr` AS `sask_nr`
			, `dat_member_banks_data`.`sutiKnuo` AS `sutik_nuo`
			, IF(`dat_member_banks_data`.`sutiKiki`='2022-01-01', '&infin;',`dat_member_banks_data`.`sutiKiki`)  AS `sutik_iki`
			, 0 AS `paid_sum`
			, `dat_pay_sums`.`pay_sum`  AS `sum_to_pay`
			, PERIOD_DIFF(
				YEAR(`dat_activities`.`pony_date_from`)*100+MONTH(`dat_activities`.`pony_date_from`)
				, IFNULL(`dat_activities`.`period_from`,202201)
			)
			   AS 
				`flag_this_period` 			
		FROM 
			`dat_nariai` 
		LEFT JOIN 
			`dat_units` ON ( 
				`dat_units`.`id`=`dat_nariai`.`id_unit`
			)
		LEFT JOIN 
			`dat_activities` ON ( 
				`dat_nariai`.`iRnr` = `dat_activities`.`id_nariai` 
			)
		LEFT JOIN 
			`dat_pay_sums` ON ( 
				`dat_nariai`.`iRnr` = `dat_pay_sums`.`id_nariai` 
			)
		LEFT JOIN 
			`dat_member_banks_data` ON ( 
				`dat_nariai`.`iRnr` = `dat_member_banks_data`.`id_nariai` 
			)
--		LEFT JOIN
--			`dat_esaskait_sutik_fin` ON (
--					`dat_member_banks_data`.`sasK_korTnr`=`dat_esaskait_sutik_fin`.`ChannelAddress`
--				AND
--					`dat_nariai`.`asmensKodas`=`dat_esaskait_sutik_fin`.`ServiceId`
--		)
		LEFT JOIN 
			`dat_bankai` ON(
			
				`dat_member_banks_data`.`bankas`=`dat_bankai`.`old_name`
		)
		LEFT JOIN
			`dat_einvoices_files` ON (

						`dat_einvoices_files`.`id_banko`=`dat_bankai`.`id`
					AND	
						`dat_einvoices_files`.`periodas`=" . $params [ 'period' ] . "						
		)		
		LEFT JOIN 
			`dat_einvoices` ON (

							`dat_nariai`.`iRnr` = `dat_einvoices`.`id_nariai`
						AND
							`dat_einvoices`.`id_einvoices_file`=`dat_einvoices_files`.`id`
		)		
		LEFT JOIN `dat_settlements` ON ( `dat_settlements`.`id_nariai` = `dat_nariai`.`iRnr` )
		WHERE  
				'" . $params [ 'period' ] . "' BETWEEN PERIOD_ADD(`dat_activities`.`period_from`,1) AND  `dat_activities`.`period_to`
			AND  
				'" . $params [ 'period' ] . "' BETWEEN `dat_pay_sums`.`period_from` AND PERIOD_ADD(`dat_pay_sums`.`period_to`,-1)
			AND 
				'" . $params [ 'period' ] . "' BETWEEN `dat_member_banks_data`.`period_from` AND PERIOD_ADD(`dat_member_banks_data`.`period_to`,-1)
			AND 
				`dat_pay_sums`.`pay_sum` > 0
			AND ( 
#																						LENGTH(`dat_member_banks_data`.`sutiKnr`)>0
				`e_inv_via` IS NOT NULL
			)
			AND 
				LENGTH(`dat_member_banks_data`.`sasK_korTnr`)>8
			AND 
				`dat_member_banks_data`.`active`
			AND 
				`dat_activities`.`active`
			AND 
				`dat_pay_sums`.`active`
			AND 
				`dat_member_banks_data`.`bankas`='" . $params [ 'bankas' ] [ 'current' ] . "'
#			AND
#				`dat_esaskait_sutik_fin`.`id`IS NOT NULL
			AND  (
					`dat_einvoices`.`id` IS NULL
					OR (
							`dat_einvoices`.`abolished`<>0
						AND
							`dat_einvoices_files`.`periodas`=" . $params [ 'period' ] . "							
						AND
						(

							(
									( `dat_einvoices`.`abolished`NOT IN(1001,10,76) )
								AND
									`dat_einvoices`.`repeat`=1
							)
							OR
							(
									( `dat_einvoices`.`abolished`IN(1001,10,76) )
								AND
									`dat_member_banks_data`.`modified`>20180201
							)
						)
					)
					OR
						`iRnr`=3072
			)
			AND (
				LENGTH(`sasK_korTnr`)=20
			)
			AND
				`dat_settlements`.`pay_period`= '" . $params [ 'period' ] . "'
			AND 
				`dat_settlements`.`effectual`
			HAVING 
				`pay_sum` > `paid_sum`
			ORDER BY
				`dat_nariai`.`iRnr`				
				";
			
		$names_fields = array ( 
	
		
		);
		
		$calcs = array ();
		
		$calc_params = array();			