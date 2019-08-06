<?php

// 		to take values for debug		
//		__LINE__  	 __FILE__ 	__DIR__ 	__FUNCTION__ 	__CLASS__ 	__TRAIT__ 	__METHOD__  __NAMESPACE__	

	function add_log_message ( $message, $log_file = 'log_file' ) {
	
		global $real_conf;
		
		$message = date ( "Y-m-d  H:i:s" ) . ' ' . $message . "\r\n";
	
		if ( ! file_put_contents ( $real_conf [ $log_file ], $message, FILE_APPEND ) ) die ( 'FATAL! ' . $message .  '...AND CANNOT SAVE  LOGS!' );
	}

	function _debug_backtrace ( $debug_backtrace_level = 'x', $glue = "\r\n" ) {
		
		$dbgtrace = debug_backtrace();
		
		$dbg_trace = '';
			
		if  ( ( $debug_backtrace_level == 'a' ) || ! _has_val ( $dbgtrace, $debug_backtrace_level ) ) { 
			
			$add_2_start = '';
			
			foreach ( $dbgtrace as $piece ) {
				
				$dbg_trace = $add_2_start . $piece [ 'file' ] . ' ( ' . $piece [ 'line' ] . ' )';
				$add_2_start = $glue;
			}
				
		} else $dbg_trace = $dbgtrace [ $debug_backtrace_level ] [ 'file' ] 
						. ' ( '  . $dbgtrace [ $debug_backtrace_level ] [ 'line' ] . ' )' ;

		return $dbg_trace;
	}	
    
	function _has_val ( $arrVar, $testKey ) { return ( isset ( $arrVar [ $testKey ] ) && $arrVar [ $testKey ] ); }	
    
	function _avihod ( $arrVar, $testKey, $default, $allow_empty = false ) {

		$reTval = $default;
		
		if  ( isset ( $arrVar [ $testKey ] ) && ( $arrVar [ $testKey ] || $allow_empty ) ) {
		
			if ( is_array ( $arrVar [ $testKey ] ) ) $reTval = $arrVar [ $testKey ];
			
			else $reTval = trim ( $arrVar [ $testKey ] );
		}
		
		return $reTval;
	}

	function errors_handler ( $errno, $errstr, $errfile, $errline ,$errcontext ) {
	
		global $dbg;
		
		$ext_debug_info = '';
		
		if ( SHOW_EXT_DEBUG_INFO ) $ext_debug_info = ' error scope vars: ' . serialize ( $errcontext ) . print_r ( debug_backtrace(), true );
		
		$location = $errfile . '(' . $errline . ')';
	
		$message = 'php_error '  . $errno . ': ' . $errstr . $ext_debug_info;
		
		if ( WORK_MODE == 'LOCAL' ) $dbg->take_errors ( $message, 1, $location );
		
		else add_log_message ( $location . "\r\n". $message );
	}
	
	function _filter3_take_from_session ( $fieldx, $suffix_session, $fltr ) { 
	
	
		foreach ( array ( '1', '2', '3' ) as $num ) {
		
			$field = $fieldx . '_' . $num; 
			$fltr -> $field = _cfihod ( $_SESSION, 'fltr_' . $field . '_' . $suffix_session, $fltr -> $field );
		}
		
		return 
			$fltr;		
	}
	
	function _filter3_take ( $fieldx, $suffix_session, $fltr ) { 
	
	
		foreach ( array ( '1', '2', '3' ) as $num ) {
		
			$field = $fieldx . '_' . $num; 
	
			if ( _cfihod ( $_POST, 'fltR' . $field, '' ) !== '' )  $_SESSION [ 'fltr_' . $field . '_' . $suffix_session ] = $fltr -> $field = trim ( $_POST [ 'fltR' . $field] );
				
			elseif  ( _have_value ( $_SESSION,  'fltr_' . $field . '_' . $suffix_session ) ) {
			
				unset ( $_SESSION [  'fltr_' . $field . '_' . $suffix_session ] );
				$fltr -> $field = '';
			}
		}
		
		return 
			$fltr;
	}
	
	function null_or_num ( $val ) {
	
		$iret = 'NULL';
	
		if ( is_numeric ( $val ) ) {
			
			$iret = $val;
		}
		return $iret;
	}
	
	function _avail_periods ( ) {
	
		    $avail_periods = array();
		    
		    for( $period = 200701; $period <= intval ( date ( "Ym" ) ); ) {
		    
			$month = $period % 100;
		    
			$avail_periods[] =  intval ( $period  / 100 ) . '-' . ( $month > 9 ? $month : '0' . $month );
			
			$period++;
			
			if ( ( $period % 100 ) > 12 ) {
			
			    $period = ( intval ( $period  / 100 ) + 1 ) * 100 + 1;
			}
		    }
		return $avail_periods;
	}

	function db_errors_log_debug_info ( $show_debug_info, $work_mode ) { 
	
		global $db, $dbg;

		$dbg->takeDbErrors ( $db->inf_db_errors, $db->inf_ex_errors, $db->runned_queries );
		if ( $db_has_errors = $dbg->log_db_errors () ) add_log_message ( $db_has_errors );

		if ( $show_debug_info ) {
		
			$dbg->reg_time ( 'end' );
			$page_creation_time = $dbg->diff_times ( 'end', 'start' );
			$page_creation_time_msg 
				= 'page creation time: ' . $page_creation_time->min . ' : ' . $page_creation_time->sec . ' : ' . $page_creation_time->usec;
		
			if ( $work_mode == 'LOCAL' ) {
		
				$dbg->show_all ( LOG_ALL_QUERIES );

				echo $page_creation_time_msg;
				
			} else {
			
				add_log_message ( 
				
							"------------------------------------------debug info------------------------------------------\r\n" 
								. $dbg->log_all ( true/* LOG_ALL_QUERIES */ ) 
							);
				
				add_log_message ( $page_creation_time_msg . 		"------------------------------------------------------\r\n" );
			}
		}
	}
	

	