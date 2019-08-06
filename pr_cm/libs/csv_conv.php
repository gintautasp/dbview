<?php

	function object1level2csv_row ( $o1level ) {
	
		$csv_row = array();
	
		foreach ( ( array ) $o1level as $prop => $val ) {
		
			$csv_row[] = '"' . $prop . ':","' . str_replace ( '"', '""', $val ) . '"';
		}
		return implode ( ',', $csv_row );
	}