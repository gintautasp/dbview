<?php

	define ( 'NAME_MODE_UNDEF', 'undef' );
	define ( 'NAME_MODE_HTML', 'html' );
	define ( 'NAME_MODE_IMAGE', 'img' );
	define ( 'NAME_MODE_AJAX', 'ajax' );
	define ( 'NAME_MODE_XML', 'xml' );
	define ( 'NAME_MODE_TEXT', 'text' );
	define ( 'NAME_MODE_CSV', 'csv' );
	
	define ( 'NAME_LOCATION_SOURCE_MAIN', 'main' );
	define ( 'NAME_LOCATION_SOURCE_ADDON', 'addon' );
	
	define ( 'ABBR_LANG_UNDEF', 'df' );	

	class request {
	
		public 
		
			$route = '', $params = array(), $path = array()
			
			, $response;
	
		function __construct ( $name_key_route, $name_script_main = 'home' ) {
				
			$this->response = new struct ( array (
			
				'name_mode' => NAME_MODE_UNDEF
				, 'name_location' => NAME_LOCATION_SOURCE_MAIN
				, 'abbr_lang' => _avihod ( $_SESSION, 'abbr_lang', ABBR_LANG_UNDEF ) 
				, 'name_script' => $name_script_main
			) );
				
			if ( _has_val ( $_REQUEST, $name_key_route ) ) {

				$this->route = array_shift ( $_REQUEST );
				$this->path = explode ( '/', $this->route );
			}
			$this->params = $_REQUEST;
			
			$this->find_mode();
		}
		
		public function find_mode () {
			
			if ( _has_val ( $this->params, 'd-img' ) ) $this->response->fields()->mode = NAME_MODE_IMAGE;
				
			elseif ( _has_val ( $this->params, 'ajax' ) ) $this->response->fields()->mode = NAME_MODE_AJAX;
				
			elseif ( _has_val ( $this->params, 'xml' ) ) $this->response->fields()->mode = NAME_MODE_XML;
			
			elseif ( _has_val ( $this->params, 'text' ) ) $this->response->fields()->mode = NAME_MODE_TEXT;
			
			elseif ( _has_val ( $this->params, 'csv' ) ) $this->response->fields()->mode = NAME_MODE_CSV;
			
			else 	$this->response->fields()->name_mode = NAME_MODE_HTML;
		}
		
		public function find_source ( array $map_scripts ) {
        
            global $log_main;
			
			if ( $this->path ) {
            
            $log_main -> x ( 'this / path', $this -> path );
			
				if ( in_array ( $this->path [0], array_keys ( $map_scripts ) ) ) { 
				
					$this->response->setn ( $map_scripts [ $this->path [0] ] );
					
					$_SESSION [ 'abbr_lang' ] = $this->response->fields()->abbr_lang;
					
				} else $this->response->setn ( $map_scripts [ 'undefined' ] );
				
			} else $this->response->setn ( $map_scripts [ $this->response->fields()->name_script ] );
		}
	}
?>