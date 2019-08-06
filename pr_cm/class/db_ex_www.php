<?php
// test
	class db_ex_www extends db_mysql_user1 {
	
		public function get_seo_link_by_link ( $lang_abbr, $link ) {
		
		
			return fetchRow (  $this->perform (
					"
				SELECT 
					`seo_link`
					,`name_view`
				FROM
					`www_seo_links`
				LEFT JOIN
					`www_links` ON (
					
						`www_links`.`id`=`www_seo_links`.`id_link`
					)
				WHERE
					`www_links`.`link` = '" . $link . "'
					"
				)
			);
		}
		
		public function is_record ( $table, $field, $wherEpart, $debug_backtrace_level = 2 ) {
		
			$retIsRecord = false;
			
			$qwIsRecord = "SELECT `$field` FROM `$table` $wherEpart";
						
			if ( $rsIsRecord = $this->perform ( $qwIsRecord, 'x', $debug_backtrace_level ) ) {
			
				if ( $rsIsRecord && ( $valIsRecord = mysqli_fetch_array ( $rsIsRecord ) ) ) {
			
					if ( isset ( $valIsRecord [ $field ] ) ) $retIsRecord  = true; 
					
					else $this->take_exec_error ( 'field not exist in to result row', $qwIsRecord, $debug_backtrace_level );
				}
			} 
			return $retIsRecord ;	
		}
		
		public function mres( $item ) {
		
			return  mysqli_real_escape_string ( $this -> ercl_db, $item );
		}

		public function insert_row_into_table ( $table, $rowArray, $debug_backtrace_level = 2, $quotes="'", $ignore = '' ) {
		
			$rsInsertRow = false;
			
			$qwInsertRow = 	"INSERT $ignore INTO `$table` (`" 
						. implode ( "`, \r\n`", array_keys ( $rowArray ) ) . "`) 
						VALUES ("
						. $this->implode_with_null ( array_map ( array ( $this, 'mres' ), array_values ( $rowArray ) ),  "$quotes, \r\n$quotes", $quotes, ", \r\n" ) 
						. ")
						";			
		
			if  ( is_array ( $rowArray ) && ( count ( $rowArray ) > 0 ) ) {
											
				$rsInsertRow = $this->perform ( $qwInsertRow, 'last_insert_id', $debug_backtrace_level  );
															
			} else  $this->take_exec_error ( 'second param - must by not empty array', $qwInsertRow, $debug_backtrace_level );
	
			return $rsInsertRow;
		}
		
		public function implode_with_null ( array $values, $quotes, $quote, $delim ) {
		
			$impl_res = '';
		
			if ( in_array ( 'NULL', $values ) ) {
			
				$impl_rs =  array();
			
				foreach ( $values as $value ) if ( $value == 'NULL' ) $impl_rs[] = $value; else $impl_rs[] = $quote . $value . $quote;
				
				$impl_res = implode ( $delim, $impl_rs );
				
			} else {
				
				$impl_res = $quote . implode ( $quotes, $values ) . $quote;
			}
			return $impl_res;
		}
	}
?>