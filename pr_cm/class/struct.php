<?php

	class struct {
	
		public $fields;
		
		function __construct( $fields = array() ) {
		
			$this->fields = new stdClass;
			
			$this->setn ( $fields );
		}
		
		public function set1 ( $field, $value ) {
		
			$this->fields->$field = $value;
		}
		
		public function setn ( $fields = array() ) {
		
			foreach ( $fields as $field => $value )
			
				$this->set1 ( $field, $value );
		}
		
		public function fields() {
		
			return $this->fields;
		}
		
		public function __get ( $name ) {
		
			return $this->fields->$name ;
		}
		
		public function __set ( $name, $value ) {
		
			$this->fields->$name = $value;
		}
	}
?>