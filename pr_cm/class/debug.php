<?php

	class debug {
	
		private $elem;
		
		public $elems = Array();
		
		public $mysql_error_info = Array(); 
		
		public $db_exec_errors = Array();
		
		public $db_queries = Array();
		
		public $bad_queries_nums = Array();
		
		public $reg_times = Array();
		
		public $errors_list = Array();		
		
		const usec_mul_c = 1000000;
	
		function __construct () {
		
			$this -> elem = new StdClass;
			$this -> reg_time ( 'start' );
		}
		
		public function takeDbErrors ( $mysql_error_info, $db_exec_errors, $db_queries ) {
		
			$this->mysql_error_info = $mysql_error_info;
			$this->db_exec_errors = $db_exec_errors;
			$this->db_queries = $db_queries;
		}
		
		public function take_errors ( $message, $dbg_level = 0, $seek_location = true ) {
		
			$error_info = new stdClass;
			$error_info->message = $message;

			if ( $seek_location ) {
		
				$dbgtrace = debug_backtrace();
			
				while ( count ( $dbgtrace ) - 1 < $dbg_level ) $dbg_level--; 
		
				if ( ( $dbg_level > -1 ) &&  isset ( $dbgtrace [ $dbg_level ] [ 'file' ] ) )
					$error_info->location = $dbgtrace [ $dbg_level ] [ 'file' ] . ' (' . $dbgtrace [ $dbg_level ] [ 'line' ] . ')';
				
				else $error_info->location = 'location unknown :( dbg_level: '. $dbg_level;
				
			} else $error_info->location = $seek_loaction;
			
			$this->errors_list[] = $error_info; 
		}
		
		public function take ( $var_name, $var_val ) {
		
			$dbgtrace = debug_backtrace();
		
			$this->elem->location = $dbgtrace [ 0 ] [ 'file' ] . ' (' . $dbgtrace [ 0 ] [ 'line' ] . ')';
			$this->elem->var_name = $var_name;
			$this->elem->var_val = print_r ( unserialize ( serialize ($var_val ) ), true );
			$this->elems[] = clone $this->elem;
		}
		
		public function reg_time ( $name ) {
		
			$this->reg_times [ $name ] = microtime ( true );
		}
		
		public function diff_times ( $to, $from ) {
		
			if ( isset ( $this->reg_times [ $to ] ) &&  isset ( $this->reg_times [ $from ] ) ) {
			
				$times_diff = $this->reg_times [ $to ] - $this->reg_times [ $from ];
				$times_diff_o = new stdClass;
				$times_diff_o->sec = ( int ) $times_diff; 
				$times_diff_o->usec =  ( int ) ( ( $times_diff - $times_diff_o->sec ) * self::usec_mul_c);
				$times_diff_o->min = 0;
				$times_diff_o->diff_float = $times_diff;
				
				if ( $times_diff_o->sec > 60 ) {
				
					$times_diff_o->min = ( int ) ( $times_diff_o->sec / 60 );
					$times_diff_o->sec = ( int ) ( $times_diff_o->sec % 60 );
				}
				
			} else $times_diff_o = false;
			
			return $times_diff_o;
		}
		
		public function log_db_errors () {
		
			$log_msg = '';
			
			if ( count ( $this->mysql_error_info ) ) {
			
				$log_msg .= "mysql errors:\r\n";
			
				foreach ( $this->mysql_error_info as $mysql_error_inf ) {
			
					$log_msg .=    
							$mysql_error_inf [ 'query_num' ] 
							. '(  ' . $mysql_error_inf [ 'mysql_errno' ] . ' - ' . $mysql_error_inf [ 'mysql_error' ] . ' ): ' . "\r\n"
							. $mysql_error_inf [ 'query' ] . "\r\n"
							. $mysql_error_inf [ 'dbgtrace' ] . "\r\n";
				}
			}
			
			if ( count ( $this->db_exec_errors ) ) {
			
				$log_msg .= "db errors: \r\n";

				foreach ( $this->db_exec_errors as $db_exec_error ) 
				
					$log_msg .=
							 $db_exec_error [ 'query_num' ] . '(  ' . $db_exec_error [ 'problem' ]   . ' ): ' . "\r\n"
							. $db_exec_error  [ 'query' ] . "\r\n"
							. $db_exec_error [ 'dbgtrace' ] . "\r\n";
			}
			return $log_msg;
		}
		
		public function log_all ( $list_db_queries ) {
		
			$log_msg = '';
			
			if ( $list_db_queries ) {
			
				$log_msg .= "db queries: \r\n";

				$ct= 0; foreach ( $this->db_queries as $db_query ) { $log_msg .=  $ct . '. ' . $db_query->loc . ":\r\n" . $db_query->query . "\r\n"; $ct++; }
			}

			if ( count ( $this->elems ) )  {
			
				$log_msg .= "taked values: \r\n";
				
				foreach ( $this->elems as $elem ) 
					$log_msg .= 'from: ' . $elem->location . ': ' .  $elem->var_name . ' ( ' . $elem->var_val . " )\r\n";
			}
			
			return $log_msg;
		}
		
		public function show_all ( $list_db_queries ) {
?>	
			<table id="t_debug">
<?php
			if ( count ( $this->mysql_error_info ) ) {
?>
				<tr><td class="dbg_header"> mysql errors:
<?php
				foreach ( $this->mysql_error_info as $mysql_error_inf ) {
			
					$this->bad_queries_nums[] = $mysql_error_inf [ 'query_num' ];
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
				
					$this->bad_queries_nums[] = $db_exec_error [ 'query_num' ];
?>
					<tr><td class="dbg_label"><?= $db_exec_error [ 'query_num' ]  . ": " . $db_exec_error [ 'dbgtrace' ] ?>
					<tr><td class="dbg_value"><pre><?= $db_exec_error [ 'query' ]  ?></pre>
					<tr><td class="dbg_error"><?= $db_exec_error [ 'problem' ] ?>
<?php
				}
			}
			
			if ( count ( $this->elems ) ) {
?>
				<tr><td class="dbg_header"> taked values:
				
<?php
				foreach ( $this->elems as $elem ) {  
?>
					<tr><td class="dbg_label"><?= $elem->location . ':' . $elem->var_name ?>
					<tr><td class="dbg_value"><pre><?= $elem->var_val ?></pre>
<?php			
				}
			}
			
			if ( count ( $this->errors_list ) ) {
?>			
				<tr><td class="dbg_header"> got php errors:
<?php		
				foreach ($this->errors_list as $error_info) {
?>
					<tr><td class="dbg_label"><?= $error_info->location ?>
					<tr><td class="dbg_value"><?= $error_info->message ?>
<?php
				}
			}
			
			if ( $list_db_queries ) {			
?>	
				<tr><td class="dbg_header"> db queries:
<?php
				$ct= 0; foreach ( $this->db_queries as $db_query ) {
				
?>
				<tr><td class="dbg_label"><?= $ct  ?>. <?= $db_query->loc ?>
				<tr><td <?= in_array ( $ct, $this->bad_queries_nums) ? 'style="color: red"' : '' ?> class="dbg_value"><pre><?= $db_query->query ?></pre>
<?php
				$ct++; }
			}			
?>
			</table>
<?php			
		}
	}
	$dbg = new debug();
?>
