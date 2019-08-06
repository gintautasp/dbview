<?php

	function IsInjected ( $str ) {

		$ret_val = false;

		$injections = array ( '(\n+)', '(\r+)', '(\t+)', '(%0A+)', '(%0D+)', '(%08+)', '(%09+)' );

		$inject = join('|', $injections);

		$inject = "/$inject/i";

		if ( preg_match ( $inject, $str) ) $ret_val = true;

		return $ret_val;
	}
	
	/*
	*    	                                      														Tikrina ar $host atitinka interneto kompiuterio vardo formata
	*/
	function is_hostname ( $host ) {

		$ret_val = true;														// ar $host pirmasis simbolis raide arba skaitmuo; ar yra bent vienas taskas; 
																		//  ar yra vien raides, skaitmenys, bruksniai ir taskai; ar pabaigoje
																		// yra TLD. t.y. a) maziausiai 2 simboliai ir b) sudarytas tik is raidziu

		if ( !preg_match ( "^[a-z0-9]{1}[a-z0-9]\.\-]*\.[a-z]{2,}$", $host ) ) $ret_val = false;
																		// ar prasmingas kompiuterio vardas, t.y. nesudarytas is
																		// paeiliui uzrasytu tasku arba bruksniu

		if ( preg_match ("\.\.", $host) || preg_match ("\.-", $host) || preg_match ( "-\.", $host ) ) $ret_val = false;

		return $ret_val;
	}

?>