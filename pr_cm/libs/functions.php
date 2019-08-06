<?php
	
	function errors_handler ( $errno, $errstr, $errfile, $errline ,$errcontext ) {
	
		global $log_main;
	
		$log_main -> php_error (  $errno, $errstr, $errfile, $errline ,$errcontext );
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