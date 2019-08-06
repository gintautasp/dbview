<?php

	function dec2hex ( $dec, $hex_len = 6, $min_hex_len=4 ) {												// used in projects: recalc 
	
		$a_hex = array();
		$a_dec2hex1 = array ( 10=>'a', 11=>'b', 12=>'c', 13=>'d', 14=>'e', 15=>'f' );
		
		while ( $dec > 16 )  {
		
			$a_hex_ = $dec % 16;
			
			if ( $a_hex_ > 9) $a_hex_ = $a_dec2hex1[ $a_hex_ ];
			
			$a_hex[] = $a_hex_;
			$dec = (int) ( $dec/ 16 );
		}
		
		if ( $dec > 9 ) $dec = $a_dec2hex1 [ $dec ];
		
		$hex = $dec;
		
		for ( $ct=count($a_hex)-1; $ct>-1; $ct-- ) $hex .= $a_hex [ $ct ];
		
		while ( strlen ( $hex ) < $min_hex_len ) $hex = '0' . $hex;
		
		while ( strlen ( $hex ) < $hex_len ) $hex = 'f' . $hex;
		
		return $hex;
	}
	
	function formaTstring ( $string, $length, $align='left', $leading=' ') {									// used in projects: nariai, webcount
	
		$stRlen = strlen ( $string ); $spcs = '';
		
			if ( $length>$stRlen ) {
			
				for ( ; $stRlen  < $length; $stRlen++ ) $spcs .= $leading;
				
				if ( $align == 'left' ) $string .= $spcs;
				
				else $string = $spcs . $string;
				
			} elseif ( $length<$stRlen ) $string = substr ( $string, 0, $length );
			
		return $string;
	}
?>