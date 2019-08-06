<?php

	class viewByQuery {
	
		public $db, $query, $names_fields, $params, $calcs, $calc_params, $sums, $sums_prev, $all_fields = array(), $groups = array(), $link_back;
		
		function __construct ( $query ="SHOW TABLES", $names_fields = array(), $params = array(), $calcs = array(), $calc_params = array(), $link_back = '' ) {

			global $db;

			$this->db = $db;
		
			$this->query = $query;
			
			$this->names_fields = $names_fields;
			
			$this->params = $params;
			
			$this->calcs = $calcs;
			
			$this->calc_params = $calc_params;
			
			$this->link_back = $link_back;
		}
		
		public function param_inp ( $param, $value ) {
		
			$param_inp = '<span class="error">param<br>input<br>error</span>';
		
			if ( is_array ( $value ) ) {
			
				$current = _cfihod ( $value, 'current', false );
				
				$options = '';
			
				if ( $values  = _cfihod ( $value, 'values', array() ) ) {
					
					foreach ( $values as $id => $option ) {
					
						$options .= '<option value="' . $id . '"' . ( $current == $id ?  ' selected="selected"' : '' ) . '>' . $option . '</option>';
					}
				}
				$param_inp = '<select  name="' . $param . '">' . $options . '</select>';
			
			} else {
			
				$param_inp =  '<input type="text" style="width: 300px" name="' . $param . '" value ="' . $value .'" placeholder="'. $param. '">';
			}
			
			return $param_inp;
		}
		
		public function params_row () {
		
			$params_row = '';
			
			if ( $this->params ) {
			
				$params_row = 
				
				'<form method="GET" action="">' 
				
				. '<div style="float: right">'
				
				. ( $this -> link_back ? '<a href="' . $this -> link_back . '">grįžti</a>' : '' )
				
				. '</div>'
				
				. '<div id="par_imp">';

				foreach ( $this->params  as $param  => $value ) {

					$params_row .= 
					
						'<div class="par1">'
							. '<label for="' . $param . '">' . $param . '</label>'
							.  $this -> param_inp ( $param, $value )
						. '</div>'
						;
			
				}
				
				$params_row .= '<input type="hidden" name="vwq" value="' . $_GET [ 'vwq' ] . '">'
							. '<div class="par1"><input type="submit" value="Vykdyti"></div>'
							. '</div></form>';
	
			}						
			return $params_row;
		}
		
		public function take() {
		
			$res = fetchArray ( $this -> db -> perform ( $this -> query ) );
			
			$table = '<table>'; $header = false; $ct = 0;
			
			foreach ( $res as $row1 ) { $ct++;
			
				if ( ! $header ) { 
				
					$table .= '<tr><th>eil.<br>nr.</th>';
				
					foreach ( $row1 as $name_col1 => $val_col1 ) {
				
						$table .= 
						
							(  
								in_array ( 'add_class', _cfihod ( $this->calcs, $name_col1, array() ) )
								
								? '<th class="' . _cfihod ( _cfihod ( $this->calc_params, $name_col1, array() ), 'class', 'undefined' ) . '">' 
								
								: '<th>'
							) 
								. 
						
							_cfihod ( $this->names_fields, $name_col1, str_replace ( '_', '<br>', $name_col1 ) ) . '</th>';
							
							$this -> all_fields[] = $name_col1;
							
							if  ( in_array ( 'subtotals', _cfihod ( $this->calcs, $name_col1, array() ) ) ) {
					
								$this->groups [ $name_col1 ] = $val_col1;
							}
					}
					
					$table .= '</tr>';
					
					$header = true;
				}
				
				$group_row = false;
				
				foreach ( $this->groups as $group1 => $val1 ) {
				
					if ( $val1 != $row1 [ $group1 ] )  {
					
						$group_row = true;
					
						$this->groups [ $group1 ] = $row1 [ $group1 ];
					}
				}
				
				if ( $group_row ) {
				
					$table .= '<tr><td></td>';
				
					foreach ( $this->all_fields as $field1 ) {
					
						$sum_tot =  _cfihod( $this->sums, $field1, '' );
						
						$sub_tot = '';
						
						if ( $sum_tot !== '' ) {
						
							$sub_tot = $sum_tot - _cfihod ( $this->sums_prev, $field1, 0 );
							
							$this->sums_prev [ $field1 ] = $sum_tot;
						}
				
						$table .= 
				
							(  
								in_array ( 'add_class', _cfihod ( $this->calcs, $name_col1, array() ) )
								
									? '<th class="' . _cfihod ( _cfihod ( $this->calc_params, $name_col1, array() ), 'class', 'undefined' ) . '">' 
								
									: '<th>'
							) 
							. $sub_tot  . '</td>';

					}					
				
					$table .= '</tr>';
				}
				
				$table .= '<tr' . ( $ct % 2 == 0 ? ' class="even"' : '' ) . '><td>' . $ct;
				
				foreach ( $row1 as $name_col1 => $val_col1 ) {
				
					$table .= 
				
						(  
							in_array ( 'add_class', _cfihod ( $this->calcs, $name_col1, array() ) )
								
								? '<td class="' . _cfihod ( _cfihod ( $this->calc_params, $name_col1, array() ), 'class', 'undefined' ) . '">' 
								
								: '<td>'
						) 			
				
						. $val_col1 . '</td>';
						
					if  ( in_array ( 'sum', _cfihod ( $this->calcs, $name_col1, array() ) ) ) {
					
						$this->sums [ $name_col1 ] = _cfihod ( $this->sums, $name_col1, 0 ) + $val_col1;
					}
				}
					
				$table .= '</tr>';
			}
			
			if ( $this->groups ) {
			
				$table .= '<tr>';
			
				
				foreach ( $this->all_fields as $field1 ) {
				
					$sum_tot =  _cfihod( $this->sums, $field1, '' );
					
					$sub_tot = '';
					
					if ( $sum_tot !== '' ) {
					
						$sub_tot = $sum_tot - _cfihod ( $this->sums_prev, $field1, 0 );
						
						$this->sums_prev [ $field1 ] = $sum_tot;
					}
			
					$table .= 
			
						(  
							in_array ( 'add_class', _cfihod ( $this->calcs, $name_col1, array() ) )
							
								? '<th class="' . _cfihod ( _cfihod ( $this->calc_params, $name_col1, array() ), 'class', 'undefined' ) . '">' 
							
								: '<th>'
						) 
						. $sub_tot  . '</td>';

				}					
			
				$table .= '</tr>';
			}			
			
			if ( $this->sums ) {
			
				$table .= '<tr>';			
			
				foreach ( $this->all_fields as $field1 ) {
				
					$table .= 
				
						(  
							in_array ( 'add_class', _cfihod ( $this->calcs, $name_col1, array() ) )
								
								? '<th class="' . _cfihod ( _cfihod ( $this->calc_params, $name_col1, array() ), 'class', 'undefined' ) . '">' 
								
								: '<th>'
						) 
						. _cfihod( $this->sums, $field1, '' ) . '</td>';

				}
				$table .= '</tr>';
				
			}
			$table .= '</table>';

			return $table;
		}
	}