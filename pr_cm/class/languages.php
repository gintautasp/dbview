<?php
	class languages {
		
		public $phrases = array();
		
		public function get_from_file ( $lang_abbr, $translations_file, $translations_var_name ) {
			
			require $translations_file;

			$this->get_lang_all ( $lang_abbr, $$translations_var_name );
			
			unset ( $$translations_var_name );
		}
		
		public function get_lang_all  ( $lang_abbr, $all_translations ) {
					
			foreach ( $all_translations as $phrase_const => $translation )  {
			
				if ( isset ( $translation [ $lang_abbr ] ) ) $this->phrases [ $phrase_const ] = $translation [ $lang_abbr ]; 
				
				elseif  ( isset ( $translation [ 'en' ] ) ) $this->phrases [ $phrase_const ] =  $translation [ 'en' ];  								// english

				elseif  ( isset ( $translation [ 'df' ] ) ) $this->phrases [ $phrase_const ] =  $translation [ 'df' ]; 								// default
				
				elseif  ( isset ( $translation [ 'eo' ] ) ) $this->phrases [ $phrase_const ] =  $translation [ 'eo' ]; 								//  esperanto		
								
				else {
				
					reset ( $translation );																				// occurred primarily in				
					$this->phrases [ $phrase_const ] = current ( $translation );					
				}
			}
		}
		
		public function get_part ( $lang_abbr, $full_translations, $part_keys ) {
			
			foreach ( $part_keys as $key ) {
			
				if ( isset ( $full_translations [ $key ] ) ) {
				
					if ( isset ( $full_translations [ $key ] [ $lang_abbr ] ) ) {
			
						 $this->phrases [ $key ] = $full_translations [ $key ] [ $lang_abbr ];
						
					} elseif ( isset ( $full_translations [ $key ] [ 'en' ] ) ) {
					
						 $this->phrases [ $key ] = $full_translations [ $key ] [ 'en' ]; 						  							// english
						
					} elseif ( isset ( $full_translations [ $key ] [ 'df' ] ) ) {
					
						 $this->phrases [ $key ] = $full_translations [ $key ] [ 'df' ];													// default
						
					} elseif ( isset ( $full_translations [ $key ] [ 'eo' ] ) ) {
					
						 $this->phrases [ $key ] = $full_translations [ $key ] [ 'eo' ];													// esperanto
						
					} else {
					
						reset ( $full_translations [ $key ] );																	// occured primarily in
						$lang_translations [ $key ] = current ( $full_translations [ $key ] );
					}
					
				} else $this->phrases [ $key ] = $key;
			}
		}
			
		public function add ( $new_translations ) {
		
			$this->phrases = array_merge ( $this->phrases, $new_translations );
		}
	
		public static function key_ident ( $key ) {
		
			return '$' . $key . '.';
		}
	
		public function trwv ( $phrase, $phrase_vars = false ) {
				
			$phrase_in_lang = _avihod ( $this->phrases, $phrase, $phrase );
												
			if ( is_array ( $phrase_vars ) ) {
					
				$phrase_vars_keys = array_keys ( $phrase_vars );
				$phrase_vars_vals = array_values ( $phrase_vars );
				
				$phrase_vars_keys_str = array_map ( array ( $this, 'key_ident' ), $phrase_vars_keys );
				
				$phrase_in_lang = str_replace ( $phrase_vars_keys_str, $phrase_vars_vals, $phrase_in_lang );	
			}
			
			elseif ( is_string ( $phrase_vars ) ) $phrase_in_lang = str_replace ( '$.', $phrase_vars, $phrase_in_lang );
			
			return $phrase_in_lang;
		}
		
		public function trps ( $phrase, $phrase_vars = false ) {
				
			$phrase_in_lang =_avihod ( $this->phrases, $phrase, $phrase );
												
			if ( is_array ( $phrase_vars ) ) $phrase_in_lang = vsprintf ( $phrase_in_lang, $phrase_vars );	
			
			elseif ( is_string ( $phrase_vars ) ) $phrase_in_lang = sprintf ( $phrase_in_lang, $phrase_vars );
			
			return $phrase_in_lang;
		}
		
		public function tr ( $phrase ) {

			return _cfihod ( $this->phrases, $phrase, $phrase );
		}
 	}
	
	$lang = new languages();
?>