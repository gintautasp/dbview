	
	function createCookie ( name, value, time_in_milisek ) {
	
		if ( time_in_milisek ) {
	
			var date = new Date();
			date.setTime (date.getTime () + (time_in_milisek));
			var expires = "; expires=" + date.toGMTString();
		}
		else { var expires = ""; }

		document.cookie = name + "=" + value+expires + "; path=/";
	}

	function readCookie ( name ) {

		var nameEQ = name + "=";
		var ca = document.cookie.split ( ';' );
	
		for ( var i = 0; i < ca.length; i++ ) {
	
			var c = ca [ i ];
			while ( c.charAt (0) == ' ' ) c = c.substring ( 1, c.length );
		
			if ( c.indexOf ( nameEQ ) == 0 ) return c.substring ( nameEQ.length, c.length );
		}
	
		return null;
	}

	function eraseCookie ( name ) {
		
		createCookie (name, "", -1);
	}