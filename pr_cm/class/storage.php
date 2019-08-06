<?php

	class storage {
	
		public $arr;
		
		public $ct = 0;
		
		public $amount; 
		
		public $piece;
		
		public $key;
		
		public $break_step = 0;
	
		function __construct ( $arr = false ) {
		
			if ( is_array ( $arr ) ) $this->on ( $arr );
				
			else {
			
				$this->arr = array();
				$this->amount = 0;
			}
		}
		
		function on ( $arr ) { $this->arr = $arr; $this->amount = count ( $arr ); }
		
		function add ( $piece ) { $this->arr[] = $piece; $this->amount++; }
		
		function addLike ( $dis , $piece ) { $this->arr [ $dis ] = $piece; $this->amount++; } 
		
		function addLikeUnique ( $dis, $piece) { if ( array_key_exists ( $dis, $this->arr ) ) { $this->add_like ( $dis, $piece ); } }
		
		function set ( $dis, $piece ) { 

			if ( array_key_exists ( $dis, $this->arr ) ) $this->arr [ $dis ]  = $piece;
			
			else $this->add_like ( $dis, $piece );
		}		
		
		function start() { $this->ct = 0; reset ( $this->arr ); }
		
		function flagStep() {
		
			return $this->break_step  && $this->ct && ( ( ( $this->ct % $this->break_step ) == 0 ) || ( $this->ct == $this->amount ) );
		}
		
		function takeValuesFrom ( $other_arr ) {
		
			foreach ( $this->arr as $key => &$val ) $val = $other_arr [ $key ];
		}
		
		function takeNext() {
			
			$ret_val = true;
			
			if ( $this->ct <  $this->amount ) { 
			
				$this->piece = current ( $this->arr );
				$this->key = key ( $this->arr );
				$this->ct++;
				next ( $this->arr );
				
			} else $ret_val = false;

			return $ret_val;
		}
	}
?>