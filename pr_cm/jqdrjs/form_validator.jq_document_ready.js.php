
	function jquery_ui_form_validator ( allFieldsIds, tipScsSsel, submitFormId ) {
		
		this.allFields = $([]);
		this.ollFields = {};
		this.tips = $( tipScsSsel );
		this.submitFormId = submitFormId;
		
		for ( var ct_fieldSid = 0; ct_fieldSid < allFieldsIds.length; ct_fieldSid++ ) {
			
			eval ( 'this.ollFields.'  + allFieldsIds [ ct_fieldSid ] + "= $( '#' + allFieldsIds [ ct_fieldSid ] )" );
			
			this.allFields.add ( $( '#' + allFieldsIds [ ct_fieldSid ] ) );
		}
			
		this.updateTips = function ( t ) {
			
			this.tips
				.text( t )
				.addClass( "ui-state-highlight" );
			
			setTimeout ( function ( ojuifv ) { 
				
					ojuifv.tips.removeClass( "ui-state-highlight", 1500 );  
				}
				, 500, this 
			);
		}		
		
		this.checkLengthStr = function ( o, min, max, which ) {
				
			if ( o.val().length > max || o.val().length < min ) {
				
				o.addClass ( "ui-state-error" );
				this.updateTips ( '"' + which + '" laukelyje turi būti nuo ' + min + " iki  " + max + " simbolių." );
				return false;
				
			} else {
			
				return true;
			}
		}
		
		this.checkRange = function  ( o, min, max, type, which ) {
			
			var oval;
			
			if ( type == 'int' ) oval = parseInt ( o.val() );
			
			else {
				
				if ( type == 'float' ) oval = parseFloat ( o.val() );
				
				else oval = $.trim( o.val() );
			}
			
			if ( ( oval > max ) ||  ( oval < min ) ) {
				
				o.addClass ( "ui-state-error" );
				this.updateTips ( '"' + which + '" laukelio reikšmė reikšmė turi būti tarp ' + min + " ir " + max + "." );
				return false;
				
			} else {
			
				return true;
			}
		}
		
		this.check_and_set_period = function ( suffix ) {
			
			frMvalid = true;
		
			period_from = $( '#eDperiod_from_year' + suffix ).val() + $( '#eDperiod_from_month' + suffix ).val();
			period_to = $( '#eDperiod_to_year' + suffix ).val() +  $( '#eDperiod_to_month' + suffix ).val();
				
			if ( period_to > period_from ) {
					
				$( '#eDperiod_from' + suffix ).val ( period_from );
				$( '#eDperiod_to' + suffix ).val ( period_to );
					
			} else {
					
				this.addError ( $( '#eDperiod_to' + suffix ), ' periodo reikšmė "iki" turi buti didesnė nei periodo "nuo"' );
				frMvalid = false; 
			}
			return frMvalid;
		}
 
		this.checkRegexp =  function ( o, regexp, message ) {
			
			if ( ! ( regexp.test ( o.val() ) ) ) {
				
				o.addClass( "ui-state-error" );
				this.updateTips( message );
				return false;
				
			} else {
				
				return true;
			}
		}
		
		this.addError = function ( o, message ) {
			
			o.addClass (  "ui-state-error" );
			this.updateTips ( message );
		}
		
		this.validateAndSubmit = function() {

			if ( this.validate() ) $( this.submitFormId ).submit();
		}
		
		this.validate = function() { return true; }
	}