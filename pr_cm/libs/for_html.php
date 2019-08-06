<?php
	function array2options ( $arr, $def='', $pri = true ) {
	
		$options_str = '';
		
		foreach ( $arr as $val => $name ) {
			
			$selected_str = '';
			
			if ( $val == $def ) $selected_str = ' selected="selected"';
			
			$options_str .= '<option value="' . $val . '"' . $selected_str . '>' . $name . '</option>'; 
		}
		if ( $pri ) echo $options_str;		
		
		else return $options_str;
	}
	
	function array2radioSarr ( $group_name, $arr, $def='', $glue_val_name = ' ', $params = '' ) {
	
		$lst_radios = array(); 
		
		foreach ( $arr  as $val => $name ) {
		
			$checked = ''; $lst_piece = '';
		
			if ( $val == $def  ) $checked = ' checked="checked"';
			
			$lst_piece .= '<input type="radio" name="' . $group_name . '"' . $params . ' value="' .  $val . '"'. $checked . '>' . $glue_val_name . $name;
			
			$lst_radios[] = $lst_piece;
		}
		return $lst_radios;		
	}
	
	function nl2rn ( $str ) {
	
		return str_replace ( "\r\n", '\n', $str );
	}
	
	function hToptions_month() {
	
		return  '
			<option value="-01">1</option>
			<option value="-02">2</option>
			<option value="-03">3</option>
			<option value="-04">4</option>
			<option value="-05">5</option>
			<option value="-06">6</option>
			<option value="-07">7</option>
			<option value="-08">8</option>
			<option value="-09">9</option>
			<option value="-10">10</option>
			<option value="-11">11</option>
			<option value="-12">12</option>
		';
	}
	
	function hToptions_year ( $start_year, $add_final_year = false ) {	
	
		$options_year = '';
	
		for ( $year = $start_year, $options_year = ''; $year <= date( "Y" ); $year++ ) { 

			$options_year .= '<option value="' . $year . '">' . $year .  '</option>';
		}
		if ( $add_final_year ) $options_year .=  '<option id="2022">2022</option>';
		
		return  $options_year;
	}
	
	function eDperiod_range ( $suffix = '', $start_year = 2012, $from_params = '', $to_params = '' , $add_final_year = true, $from_def = false, $to_def  = false ) {
	
		$options_year = hToptions_year( $start_year, true );
		
		$options_month = hToptions_month();
				
		$def_from_year = ''; $def_from_month = ''; $def_to_year = ''; $def_to_month = '';
		
		if ( $from_def ) {
		
			$def_from_year = ' value="' . substr ( $from_def, 0, 4 ) .'" '; $def_from_month = ' value="-' . substr ( $from_def, 5, 2 ) . '" '; 
		}
		if ( $to_def ) {
		
			$def_to_year = ' value="' . substr ( $to_def, 0, 4 ) .'" '; $def_to_month = ' value="-' . substr ( $to_def, 5, 2 ) . '" '; 
		}		
?>	
		<table>
		<tr>
		<td colspan="2">
		<label for="eDperiod_from<?= $suffix ?>">Periodas nuo</label>
		</td>
		<td colspan="2">
		<label for="eDperiod_to<?= $suffix ?>"> iki</label>
		</td>
		<tr>
		<td>
		<select id="eDperiod_from_year<?= $suffix ?>" class= "text ui-widget-content ui-corner-all" style="width: 70px; padding: 5px" <?= $from_params . $def_from_year  ?>>
			<?= $options_year ?>
		</select>
		<td>
		<select id="eDperiod_from_month<?= $suffix ?>"  class= "text ui-widget-content ui-corner-all" style="width: 50px; padding: 5px" <?= $from_params . $def_from_month ?>>
			<?= $options_month ?>
		</select>
		<td>
		<select id="eDperiod_to_year<?= $suffix ?>" class= "text ui-widget-content ui-corner-all" style="width: 70px; padding: 5px" <?= $to_params . $def_to_year ?>>
			<?= $options_year ?>
		</select>
		<td>
		<select id="eDperiod_to_month<?= $suffix ?>" class= "text ui-widget-content ui-corner-all" style="width: 50px; padding: 5px" <?= $to_params . $def_to_month ?>>
			<?= $options_month ?>
		</select>		
		</table>
		<input type="hidden" name="eDperiod_from<?= $suffix ?>" id="eDperiod_from<?= $suffix ?>" value="0">		
		<input type="hidden" name="eDperiod_to<?= $suffix ?>" id="eDperiod_to<?= $suffix ?>" value="0">
<?
	}
	
	function hTperiod ( $label, $def, $preffix = 'fltR', $start_year = 2012,  $suffix = '', $params = '' ) {
	
		$options_year = hToptions_year( $start_year );
		
		$options_month = hToptions_month();

		if ( $def ) {
		
			$def_year = ' value="' . substr ( $def, 0, 4 ) .'" '; $def_month = ' value="-' . substr ( $def, 5, 2 ) . '" '; 
		}
?>
		<table>
		<tr><td colspan="2"><label for "<?= $preffix ?>period<?= $suffix ?>"><?= $label ?></label>
		<tr>
		<td>
			<select id="<?= $preffix ?>year<?= $suffix ?>" class= "text ui-widget-content ui-corner-all" style="width: 70px; padding: 5px" <?= $params . $def_year  ?>>
				<?= $options_year ?>
			</select>
		<td>
			<select id="<?= $preffix ?>month<?= $suffix ?>"  class= "text ui-widget-content ui-corner-all" style="width: 50px; padding: 5px" <?= $params . $def_month ?>>
				<?= $options_month ?>
			</select>
		</table>
		<input type="hidden" name="<?= $preffix ?>period<?= $suffix ?>" id="<?= $preffix ?>period<?= $suffix ?>" value="0">

<?
	}
?>