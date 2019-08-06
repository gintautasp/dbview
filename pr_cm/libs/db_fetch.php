<?php
	function fetchArray ( $result, $row_function = false, $row_function_params = false ) {
		
		$resultArray = array();
			
		if ( $result ) {
			
			while ( $row = mysqli_fetch_assoc ( $result ) ) {
				
				if ( $row_function ) 
					$row = call_user_func ( $row_function, $row, $row_function_params );
						
					$resultArray[] = $row;
				}
			}
			return $resultArray;
		}
        
	function fetchArr1 ( $result, $field, $row_function = false, $row_function_params = false ) {
		
		$resultArray = array();
			
		if ( $result ) {
			
			while ( $row = mysqli_fetch_assoc ( $result ) ) {
				
				if ( $row_function ) 
					$row = call_user_func ( $row_function, $row, $row_function_params );
						
					$resultArray[] = $row [ $field ];
				}
			}
			return $resultArray;
		}
/*		
	function fetchObjectsArray ( $result, $row_function = false, $row_function_params = false ) {
		
		$resultArray = array();
			
		if ( $result ) {
			
			while ( $row = mysql_fetch_object ( $result ) ) {
				
				if ( $row_function ) 
				
					$row = call_user_func ( $row_function, $row, $row_function_params );
						
					$resultArray[] = $row;
				}
			}
			return $resultArray;
		}
*/		
	function fetchObjectsArray ( $result, $key_column = false, $row_function = false, $row_function_params = false ) {
		
		$resultArray = array();
			
		if ( $result ) {
			
			while ( $row = mysqli_fetch_object ( $result ) ) {
				
				if ( $row_function ) 
				
					$row = call_user_func ( $row_function, $row, $row_function_params );
						
					if ( $key_column ) $resultArray[ $row->$key_column ] = $row;
					else $resultArray[] =  $row;
				}
			}
			return $resultArray;
		}		

	function fetchOptions ( $result, $field_val, $field_item, $selected = false, $field_disabled = false, $field_style = false ) {
	
		$res_str = ''; 
			
		if ( $result ) {
			
			while ( $row = mysqli_fetch_object ( $result ) ) {
			
				$selected_str = ''; $disabled_str = ''; $style = '';
			
				if ( $selected && ( $selected == $row->$field_val ) ) $selected_str = ' selected="selected"';
				
				if ( $disabled &&  intval ($row->$field_disabled ) ) $disabled_str = ' disabled="disabled"';
				
				if ( $field_style &&  strlen ( $row->$field_style ) ) $style = ' style="' . $row->$field_style . '"';				
				
				$res_str = '<option' . $selected . $disabled . $style .  '>' . $row->$field_val . '</option>';
				
				}
				return $resultArray;
			}
		}

		
	function fetchAssoc ( $result, $key_column, $val_column = false, $row_function = false, $row_function_params = false )  {	
		
		$resultArray = array();
			
		if ( $result ) {
			
			while ( $row = mysqli_fetch_assoc ( $result ) ) {
				
				if ( $row_function ) 
					$row = call_user_func ( $row_function, $row, $row_function_params );
						
				$resultArray [ $row [ $key_column ] ] = $val_column ? $row [ $val_column ] : $row;
			}
		}
		return $resultArray;
	}
	
	function fetchRow ( $result ) {					
		
		$reTval = false;			
			
		if ( $result ) $reTval = mysqli_fetch_object ( $result );			
			
		return $reTval;		
	}	
	
	function fetchValue ( $value_name, $rsGetValue ) {
					
		$value_result = false;			
			
		if ( $rsGetValue  ) {
			
			if ( $value_row = mysqli_fetch_assoc ( $rsGetValue ) ) {
			
				if ( isset ( $value_row [ $value_name ] ) ) $value_result = $value_row [ $value_name ]; 
			} 
		}
		return $value_result;
	}
