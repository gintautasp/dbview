<?php

	const 
		mysql_max_unsigned_int = 	4294967295 // mysql max unsigned int reikšmė
			;
	/**
	 * Patikrina ar reikšmė papuola į intervalą. Intervalas tikrinamas imtinai.
	 * Jeigu $from yra null, tuomet, tenkins visos reikšmės, kurios yra <= $to.
	 * Jeigu $to yra null, tuomet, tenkins visos reikšmės, kurios yra >= $from.
	 * @param mixed $test - testuojama reikšmė
	 * @param mixed $from - Pradžios rėžis (arba null)
	 * @param mixed $to - Pabaigos rėžis (arba null)
	 * @return bool
	 */
	function _between( $test, $from, $to ) {

		if ( is_null( $test ) ) {

			return false;
		}

		return ( ( $test >= $from ) || is_null( $from ) ) && ( ( $test <= $to ) || is_null( $to ) );
	}
	
	/*
	* Grazina asociatyvaus masyvo arba objekto savybes reiksme, pagal nurodyta indeksa (savybes pavadinima) 					(eng.  [A]rray [V]alue [I]f [H]as [O]r [D]efault - avihod )
	* arba nurodyta reiksme pagal nutylejima jei reiksme nenustatyta (nera masyve arba objekte) 							(eng. return value of asscociative array $arr element if it is set with $key)
	*@param $arr mixed - associative array or object  												(eng. or return $default value if not set. )
	*@param $key - string masyvo indeksas arba objekto savybe
	*@return mixed
	*/
	function _cfihod ( $arr, $key, $def ) {
	
		$iret = $def;
		
		if ( is_array ( $arr ) && isset ( $arr [ $key ] ) ) $iret = $arr [ $key ];
		
		if ( is_object ( $arr ) && ( method_exists ( $arr, '__get' ) || property_exists ( $arr, $key ) ) ) $iret = $arr->$key; 
				
		return $iret;
	}
	
	function _addAroundOrIfNot3 ( $add_before, $value, $add_after = '', $value_if_not = '' ) {
	
		return ( is_string ( $value ) && ( $value !== '' ) ) ? ( $add_before . $value . $add_after ) : $value_if_not;
	}
	
	function _correctPath ( $path, $dir_sep = '/' ) {
	
		$file_name_pos = strrpos ( $path, $dir_sep );
		
		$file_name = substr ( $path, $file_name_pos );
		
		$dir = realpath ( substr ( $path, 0, $file_name_pos ) );
		
		$correct_path = $dir . $file_name;
		
		return $correct_path;
	}
    
    function _dir_fileFromPath  ( $path, $dir_sep = '/' ) {
	
		$file_name_pos = strrpos ( $path, $dir_sep );
        
        $res = new stdClass;
		
		$res -> file_name = substr ( $path, $file_name_pos + 1 );
		
		$res -> dir = realpath ( substr ( $path, 0, $file_name_pos ) );
        
        $dir_sep = '\\';
        
		$up_dir_pos = strrpos ( $res -> dir, $dir_sep );
		
		$res -> sub_dir = substr ( $res -> dir , $up_dir_pos + 1 );
		
		$res -> up_dir = realpath ( substr ( $res -> dir, 0, $up_dir_pos ) );
        
        return $res;
    }
    
	function _have_value ( $arrVar, $testKey ) { return ( isset ( $arrVar [ $testKey ] ) && $arrVar [ $testKey ] ); }