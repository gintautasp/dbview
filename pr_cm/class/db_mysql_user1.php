<?php

	class db_mysql_user1 {
		
		public $name_db = ''
		
			, $status = 'disconnected'
		
			, $flag_got_errors = false, $flag_log_queries = false
		
			, $ercl_db = false 									// erlc - external resource connection link
		
			, $runned_queries = array()
		
			, $inf_db_errors = array(), $inf_ex_errors = array()
		
			, $last_insert_id = 0, $last_afected_rows = 0, $mysql_num_rows = 0
		;
		
		function __construct ( $name_db, $name_user_db = 'root', $password_db = '', $log_queries = false, $name_server_db = 'localhost', $persistent = false ) {
		
			global $lang;
		
			if ( ! empty ( $name_db ) ) { 
				
				$this->name_db = $name_db;
				
				if ( $persistent )  $fnRet = $this->ercl_db = mysql_pconnect ( $db_server, $db_user, $db_password );
			
				else 	$this->ercl_db = mysqli_connect ( $name_server_db, $name_user_db, $password_db );	

				if ( ! $this->ercl_db ) {
			
					$this->take_mysql_error ( $lang->tr ( 'DB_CANNOT_CONNECT_TO' ), "CONNECT DB" );  
					
				} else {
				
					mysqli_set_charset ( $this->ercl_db, 'utf8' );
					
					$this -> status = 'connected';
					
					$this -> ready_db();
				}
				
			} else $this->take_exec_error ( $lang->tr ( 'DB_INCORRECT_NAME' ), "USE `" . $name_db . "`" );	
			
			$this->flog_log_queries = $log_queries;
		}
		
		public function get_connection () {
			
			return $this -> ercl_db;
		}
		
		public function ready_db ( $name_db = false ) { 
		
			if ( $name_db && !empty ( $name_db ) && $this->name_db != $name_db )  $this->name_db = $name_db;

			if ( ! mysqli_select_db ( $this->ercl_db, $this->name_db ) ) $this->take_mysql_error ( 'USE `' . $this->name_db . '`' );
			
			else $this->status = 'ready';		
		}
		
		public function trim_query_text ( $query_text ) {
		
			global $log_main;
		
			$lines = explode ( "\n", $query_text );
			$cut_tabs =  strrpos ( $lines [ 0 ], "\t" );
			
			$log_main -> x ( $cut_tabs, 'CUT FROMM' );
			
			foreach ( $lines as & $line ) $line = substr ( $line, $cut_tabs );
			
			return implode ( "\r\n" . $lines );
		}
		
		public function take_mysql_error ( $query, $debug_backtrace_level = 'a' ) {
		
			$this->inf_db_errors[] = array ( 
								'mysql_errno' =>  $this->ercl_db -> errno
								, 'mysql_error' => $this->ercl_db -> error
								, 'query_num' => ( $this->flag_log_queries ? ( count ( $this->runned_queries ) - 1 ) : '?' )
								, 'query' => str_replace ( "\t", '', $query ) //$this->trim_query_text ( $query ) // 
								, 'dbgtrace' => _debug_backtrace ( $debug_backtrace_level ) 
							);
			$this->flag_got_errors = true;
		}
		
		public function take_exec_error ( $problem, $query, $debug_backtrace_level = 'a' ) {
				
			$this->inf_ex_errors[] = array (
								'problem' => $problem
								, 'query_num' => ( $this->log_queries ? ( count ( $this->runned_queries ) - 1 ) : '?' )
								, 'query' => $this->trim_query_text ( $query ) //str_replace ( "\t", '', $query )
								, 'dbgtrace' => _debug_backtrace ( $debug_backtrace_level ) 
							);
			$this->flag_got_errors = true;
		}

		public function takings_to_0 ( $take ) {
		
			if ( $take == 'affected_rows' ) $this->last_afected_rows = 0;
					
			if ( $take == 'last_insert_id' ) $this->last_insert_id = 0;

			if ( $take == 'mysql_num_rows' ) $this->mysql_num_rows = 0;
		}
		
		public function perform ( $query, $take = 'x', $debug_backtrace_level = 2, $log = false ) {
						/*
            global $log_main;
            
            $log_main -> x ( $query, 'perform_query' );
                        */
			if ( $this->flag_log_queries || $log ) { 
			
				$log_query = new stdClass;
				
				$log_query->query = htmlspecialchars ( str_replace ( "\t", '', $query ) );
				$log_query->loc = _debug_backtrace ( $debug_backtrace_level, debug_backtrace() );
			
				$this->runned_queries[] = $log_query;
			}
		
			$rsQuery = false;
		
			if ( ! empty ( $query ) && ( $this->status == 'ready' ) ) {
			
				if  ( ! ( $rsQuery = mysqli_query ( $this -> ercl_db, $query ) ) ) {
				
					$this->take_mysql_error ( $query, $debug_backtrace_level );
					
					$this->takings_to_0 ( $take );
					
				} else  {
				
					if ( $take == 'affected_rows' ) $this -> last_afected_rows = $this->ercl_db -> affected_rows;
					
					if ( $take == 'last_insert_id' ) $this -> last_insert_id = $this->ercl_db -> insert_id;
					
					if ( $take == 'mysql_num_rows' ) $this->mysql_num_rows = $rsQuery -> num_rows;
				}
				
			} else { 
			
				if ( empty ( $query ) ) $this->take_exec_error ( 'query was empty', $query, $debug_backtrace_level );
				
				if ( $this->status != 'ready' ) $this->take_exec_error ( 'db not ready', $query ); 
				
				$this->takings_to_0 ( $take );
			}
			
			return $rsQuery;
		}
		
		public function add ( $table, array $keys_values, $debug_backtrace_level = 2, $log = false  ) {
		//global $log_main;
			$qwAddRow =
					"
				INSERT INTO
					`" . $table . "` (`" . implode ( '`, `', array_keys ( $keys_values ) ) . "`)
				VALUES
					(
					'" . implode ( "', '", $keys_values  ) . "'
					)
					";
        // $log_main -> x ( $qwAddRow, '$qwAddRow' );
			return $this->perform ( $qwAddRow, 'last_insert_id', $debug_backtrace_level, $log  );
		}
		
		public function set ( $table, array $keys_values, $condition, $debug_backtrace_level = 2, $log = false  ) {
		
			$set_lst = array(); 
			
			foreach ( $keys_values as $key => $value ) 

				$set_lst[] = "`" . $key . "`='" . $value . "'";
				
			$set_str = implode ( ', ', $set_lst );
		
			$qwSet =
					"
				UPDATE
					`" . $table . "` 
				SET 
					" . $set_str . "
				WHERE
					" . $condition . "
					";
			return $this->perform ( $qwSet, 'affected_rows', $debug_backtrace_level, $log  );
		}

		public function get ( $fields = '*', $table, $condition, $order = false, $limit = false, $debug_backtrace_level = 2, $log = false ) {
		
			$qwGet =
					"
				SELECT 
					" . $fields . "
				FROM 
					" . $table . "
				WHERE
					" . $condition . "
				" . ( $order ? 'ORDER' : '' ) . "
					" . $order . "
				" . ( $limit ? 'LIMIT' : '' ) . "
					" .  $limit . "
					";
			return $this->perform ( $qwGet, 'mysql_num_rows', $debug_backtrace_level, $log  );					
		}
	}
