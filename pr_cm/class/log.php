<?php

	require_once  $conf [ 'dir_commons' ] .  '/libs/csv_conv.php';

	class log {
	
		const usec_mul_c =  100000;

		public $access_all = true, $log_open = false, $log_file, $messages = array()
			, $mysql_error_info = array()
			, $db_exec_errors =array()
			, $db_queries		
		;
		
		
		function __construct ( $log_file ) {
		
			$this -> log_file = $log_file;
		
			$this -> log_context();
		}
		
		function new_message () {

			$message = new stdClass;	
			
			$message -> time = time();
			
			return $message;
		}		
		
		function message_context () {

			$message = $this -> new_message();
			
			$message -> type = 'context';
			
			$message -> date_time = date ( "Y-m-d H:i:s" ); 
			
			$message -> request_uri = $_SERVER [ 'REQUEST_URI' ];
			$message -> get = print_r ( $_GET, true );
			$message -> post = print_r ( $_POST, true );
			
			return $message;
		}

		function log_context() {
		
			if ( $this -> access_all && ! $this -> log_open  ) {
			
				$message = $this -> message_context ();
				// print_r ( $message );
				$this -> messages [] = $message;
				$this -> add_log_message ( object1level2csv_row ( $message ) );
				
				$this -> log_open = true;
			}
		}
		
		function add_log_message ( $message ) {
		
			if ( ! file_put_contents ( $this -> log_file, $message . "\r\n", FILE_APPEND ) ) 
			
				die ( 'FATAL! ' . $message .  '...AND CANNOT SAVE  LOGS!' );
		}		
		
		
		function x ( $var, $name = 'i was lazy to set name', $file = __FILE__, $line = __LINE__ ) {
		
			$bt = debug_backtrace();
			$caller = array_shift($bt);		
			
			$message = $this -> new_message();	

			$message -> type = 'var';
			
			$message -> name = $name;
			
			$message -> file = $caller [ 'file' ];
			
			$message -> line = $caller [ 'line' ];			
			
			$message -> val = $this -> var4hum ( $var );

			$this -> log ( $message );
		}

		function php_error (  $errno, $errstr, $errfile, $errline, $errcontext ) {
		
			$message = $this -> new_message();
			
			$message -> type = 'php error';
				
			$message -> errno = $errno;
			$message -> errstr = $errstr;
			$message -> errfile = $errfile;
			$message -> errline = $errline;
			
			if ( ERROR_SCOPE_VARS === true ) {

				$message -> error_scope_vars = serialize ( $errcontext );
			}

			if ( DEBUG_BACKTRACE === true ) {
			
				$message -> debug_backtrace = print_r ( debug_backtrace(), true ); 
			}		
			$this -> log ( $message );
		}
		
		function mark ( $info, $file = __FILE__, $line = __LINE__  ) {
		
			$message = new_message();
			
			$message -> type = 'mark info';
		
			$message -> file = $file;
			
			$message -> line = $line;		
				
			$message -> info = $info;			
		
			$this -> log ( $message ); 
		}
		
		function log ( $message ) {
		
			$this -> log_context();
			
			$this -> messages [] = $message;
			$this -> add_log_message ( object1level2csv_row ( $message ) );
		}
		
		public function takeDbErrors ( $mysql_error_info, $db_exec_errors/*, $db_queries  */) {
		
			$this->mysql_error_info = $mysql_error_info;
			$this->db_exec_errors = $db_exec_errors;
			// $this->db_queries = $db_queries;
		}
		
		public function diff_times ( $to, $from ) {
		
			$times_diff_o = new stdClass;
			
			$times_diff_o -> sec = 0;
			$times_diff_o -> usec = 0;
			$times_diff_o -> min = 0;
			$times_diff_o -> diff_float = 0;
		
			if ( $to &&  $from ) {
			
				$times_diff = $to - $from;
				$times_diff_o = new stdClass;
				$times_diff_o->sec = ( int ) $times_diff; 
				$times_diff_o->usec =  ( int ) ( ( $times_diff - $times_diff_o->sec ) * self::usec_mul_c);
				$times_diff_o->min = 0;
				$times_diff_o->diff_float = $times_diff;
				
				if ( $times_diff_o->sec > 60 ) {
				
					$times_diff_o->min = ( int ) ( $times_diff_o->sec / 60 );
					$times_diff_o->sec = ( int ) ( $times_diff_o->sec % 60 );
				}
			}
			
			return $times_diff_o;
		}

		public static function var4hum ( $var_to_log, $format_complex = 'pus' ) {
		
			$var_hum = $var_to_log;
		
			if ( is_bool ( $var_to_log ) ) { if ( ! $var_to_log ) $var_hum = '#~false'; else $var_hum = '#~true'; }
			
			if ( is_numeric ( $var_to_log ) && ( $var_to_log == 0 ) ) $var_hum = '#~0';
			
			if ( is_string ( $var_to_log ) && empty ( $var_to_log ) ) $var_hum = '#~empty string';

			if ( is_null ( $var_to_log  ) ) $var_hum = '#~NULL';
			
			if ( is_object ( $var_to_log ) || is_array ( $var_to_log ) ) $var_hum = self::$format_complex ( $var_to_log );

			return $var_hum;
		}

		public static function ser ( $var ) {

			return "#~serialized:\r\n" . serialize ( $var );
		}

		public static function ve ( $var ) {

			return "#~exported:\r\n" . var_export ( $var );
		}
		/*
		* kintamasis var keiciamas i string'a reikšmių log'iniui vizualiam pareikimui
		* @param mixed $var
		* @return sring        
		*/    
		public static function pus ( $var ) {
		
			return "#~pus`ed:\r\n" . print_r ( unserialize ( serialize ( $var ) ), true );
		}
		
		function see() {
		
			$started = 0;
?>		
			<table id="t_debug">
<?php
			// print_r ( $this -> messages );
			
			if ( count ( $this->mysql_error_info ) ) {
?>
				<tr><td class="dbg_header"> mysql errors:
<?php
				foreach ( $this->mysql_error_info as $mysql_error_inf ) {
			
					// $this->bad_queries_nums[] = $mysql_error_inf [ 'query_num' ];
?>
					<tr><td class="dbg_label"><?= $mysql_error_inf [ 'query_num' ] . ': ' . $mysql_error_inf [ 'dbgtrace' ] ?>
					<tr><td class="dbg_value"><pre><?= $mysql_error_inf [ 'query' ] ?></pre>
					<tr><td class="dbg_error"><?= $mysql_error_inf [ 'mysql_errno' ] . ': ' . $mysql_error_inf [ 'mysql_error' ] ?>	
<?php
				}
			}
			
			if ( count ( $this->db_exec_errors ) ) {
?>
				<tr><td  class="dbg_header"> db errors:
<?php
				foreach ( $this->db_exec_errors as $db_exec_error ) {
				
					// $this->bad_queries_nums[] = $db_exec_error [ 'query_num' ];
?>
					<tr><td class="dbg_label"><?= $db_exec_error [ 'query_num' ]  . ": " . $db_exec_error [ 'dbgtrace' ] ?>
					<tr><td class="dbg_value"><pre><?= $db_exec_error [ 'query' ]  ?></pre>
					<tr><td class="dbg_error"><?= $db_exec_error [ 'problem' ] ?>
<?php
				}
			}			
			
			foreach ( $this -> messages as $message1 ) {
			
				if ( $message1 -> type == 'context' ) $started = $message1 -> time;
				
				$diff_time = $this -> diff_times ( $message1 -> time, $started );
				
				switch ( $message1 -> type ) {
			
					case 'context': 
?>
					<tr>
						<td  class="dbg_header">
							<?= 
								_addAroundOrIfNot3 ( '', _cfihod ( $message1, 'date_time', '' ), ': ', '' ) 
								. ' [' . $diff_time -> min . ':' . $diff_time -> sec . ':' . $diff_time -> usec . '] '
								. $message1 -> type 
							?>
						</td>
					</tr>
<?php
					break;
					
					case 'php error': // print_r ( $message1 );
?>
					<tr>
						<td  class="dbg_header">
							<?= 
								' [' . $diff_time -> min . ':' . $diff_time -> sec . ':' . $diff_time -> usec . '] '
								. $message1 -> type 
							?>
						</td>
					</tr>
					<tr><td class="dbg_label"><?= $message1 -> errfile . ': ' . $message1 -> errline ?></tr>
					<tr><td class="dbg_value"><?= $message1 -> errstr  ?></td></tr>
					<tr><td class="dbg_error"><?= $message1 -> errno  ?></td></tr>
<?php
					break;

					case 'var': 
?>
					<tr>
						<td  class="dbg_header">
							<?= 
								' [' . $diff_time -> min . ':' . $diff_time -> sec . ':' . $diff_time -> usec . '] '
								. $message1 -> name 
							?>
						</td>
					</tr>
					<tr><td class="dbg_label"><?= $message1 -> file . ': ' . $message1 -> line ?></tr>
					<tr><td class="dbg_value"><?= $message1 -> val  ?></td></tr>
<?php
					break;
					
					case 'mark info': 
?>
					<tr>
						<td  class="dbg_header">
							<?= 
								' [' . $diff_time -> min . ':' . $diff_time -> sec . ':' . $diff_time -> usec . '] '
							?>
						</td>
					</tr>
					<tr><td class="dbg_label"><?= $message1 -> file . ': ' . $message1 -> line ?></tr>
					<tr><td class="dbg_value"><?= $message1 -> info  ?></td></tr>
<?php
					break;					
				}
			}
?>
			</table>
<?php
		}
	}
	
	$log_main = new log ( $conf [ 'main_log_file' ] ); 