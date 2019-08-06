
	function take_eD_period_range( suffix ) {
	
		start_year = parseInt ( $( '#eDperiod_from_year' + suffix ).val() );
		start_month = Math.abs( parseInt ( $( '#eDperiod_from_month' + suffix ).val() ) );
		
		start_period = start_year * 100 + start_month;
		
		finish_year = parseInt ( $( '#eDperiod_to_year' + suffix ).val() );
		finish_month = Math.abs( parseInt ( $( '#eDperiod_to_month' + suffix ).val() ) );
		
		finish_period = finish_year * 100 + finish_month;
		
		return { 'from': start_period, 'till': finish_period };
	}
	
	function between( val, from, till ) {
		
		return  ( val >= from ) && ( val <= till );
	}

	function period_to_imputs( suffix ) {
		
		period_from = $( '#eDperiod_from_year' + suffix ).val() + $( '#eDperiod_from_month' + suffix  ).val().substr(1,2);
		period_to = $( '#eDperiod_to_year' + suffix ).val() +  $( '#eDperiod_to_month' + suffix  ).val().substr(1,2);		
			
		$( '#eDperiod_from' + suffix ).val ( period_from  );
		$( '#eDperiod_to' + suffix ).val ( period_to );
	}


	function today_date_str () {
	
		today = new Date();
		diSmonth = today.getMonth() + 1;
		diSday = today.getDate();
		
		return today.getFullYear() + '-' + ( diSmonth>9 ? diSmonth : '0' + diSmonth ) + '-' + ( diSday>9 ? diSday : '0' + diSday );
	}
	
	function blink_message ( place, message ) {

		$( place ).text ( message).addClass( "ui-state-highlight" );
					
			setTimeout ( function () { 
				
				$( place ).removeClass( "ui-state-highlight", 1500 ); 
						
		}, 500 );
	}
	
	function blink_place ( place ) {

		$( place ).addClass( "ui-state-highlight" );
					
			setTimeout ( function () { 
				
				$( place ).removeClass( "ui-state-highlight", 1500 ); 
						
		}, 500 );
	}
	
	function selected_recs() {
		
		this.amount = 0;
		
		this.count = function() {
			
			haveSels = 0;
			dis_sels = [];
			
			$( '.sLrecord:checked' ).each ( function() {
				
				dis_sels [ haveSels ] = $( this ).val();
				haveSels++;
			});
			
			this.sels = dis_sels;
			
			this.amount = haveSels;
		}
	}
	
	sl_recs = new selected_recs();

	$( ".tOdeep" ).button ({
		
            icons: {
		    
                primary: "ui-icon-extlink"
            }
	    ,	text: false
        });
	
	$( ".sfarrup" ).button ({
		
		icons: {
			
			primary: "ui-icon-arrowthick-1-n"
		}
		, text: false
	});
	
	$( ".sfarrdw" ).button ({
		
		icons:{
			
			primary: "ui-icon-arrowthick-1-s"
		}
		, text: false
	});

	$( ".sfarrno" ).button ({
		
		icons:{
			
			primary: "ui-icon-grip-dotted-vertical"
		}
		, text: false
	});

	$( "#control" ).draggable();
	
	$( ".juIbutton" ).button();
	
	$( '#dLto_group' ).dialog ({
		
		autoOpen: false
		, height: 250
		, width: 300
		, modal: true
		, closeText: "uždaryti"
		, buttons: {
			"Priskirti grupei": function() {
				
				frMok = true;
				
				if ( ($( '#tOgroup' ).val() ).length == 0 ) { 
			
					$( '#tOgroup' ).val ( $( '#seLtOgroup option:selected' ).html() );
			
				} else {
			
					if ( ! between ( ( $( '#tOgroup' ).val() ).length, 3, 63 ) ) {
					
						blink_message ( '#to_group_notes', "Grupės pavadinimas ilgis turi buti tarp 3 ir  63." );
						frMok = false;
					}
				}				
				if ( frMok ) {
					
					$( '#frMgrPdo' ).val ('<?= RECORDS_ACTION_ASSIGN_TO_GROUP ?>' );
					$( '#frMgrouPactions' ).submit();
				}
			}
			,"Atšaukti": function() {
			
				$( this ).dialog( 'close' );
			}
		}
	});	
	
	$( '#cBto_group' ).click ( function ( e ) {
			
		var ret = false;
		
		sl_recs.count();
		
		if ( sl_recs.amount > 0 )  { $( '#dLto_group' ).dialog( 'open' ); }
		
		else {
			
			blink_message ( '#control_notes', "Turite parinkti bent vieną padalinį" );
			frMok = false;			
		}
	});
	
	$( '#cBchange_activity' ).click ( function ( e ) {
			
		var ret = false;
		
		sl_recs.count();
		
		if ( sl_recs.amount == 1 )  { 
			
			diSid = $( '.sLrecord:checked' ).val(); change_activity = false;
			
			if ( $( '#av' + diSid ).html() == '1' ) {
			
				if ( parseInt ( $( '#m' + diSid ).html() ) > 0 ) blink_message ( '#control_notes', "Padalinys turi aktyvių narių!" );
				
				else change_activity = true;
				
			} else change_activity = true; 
			
			if ( change_activity ) {
			
				$( '#frMgrPdo' ).val ( '<?= RECORDS_ACTION_CHANGE_ACTIVITY ?>' );
				$( '#frMgrouPactions' ).submit();
			}
			
		} else {
			
			blink_message ( '#control_notes', "Turite parinkti vieną padalinį" );
			frMok = false;			
		}
	});	
	
	$( '#c0' ).click ( function() {
		
		$( '.sLrecord' ).each ( function() {
			
			var ida = ida = $(this).val();
			
			if ( $( this ).prop ( 'checked' ) ) { 
				
				$( this ).prop ( 'checked', false );
				$( '#dr' + ida ).attr ( 'class', $( '#dr' + ida ).attr ( 'rel' ) );
			}
			else {
				
				$( this ).prop ( 'checked', true );
				$( '#dr' + ida ).attr ( 'class', 'selected_row' );				
			}
		});
	});

	$( '.sLrecord' ).click ( function() {
		
		var ida = $(this).val();
		
		if ( $( this ).prop ( 'type' ) == 'radio' ) { 
			
			$( 'table.data tr' ).each ( function() {
			
				$( this ).attr ( 'class', $( this ).attr ( 'rel' ) );
			});
		} 
		if ( $( this ).prop ( 'checked' ) )  $( '#dr' + ida ).attr ( 'class', 'selected_row' );
		
		else $( '#dr' + ida ).attr ( 'class', $( '#dr' + ida ).attr ( 'rel' ) );
	});	
	
	$( 'a.for_title' ).click ( function(e) {
		
		e.preventDefault();
	});
	
	$( document ).tooltip();
	
	$( '.noclick' ).click( function ( e ) {
		
		e.preventDefault();
	});
	
	var acts_msgs = false;
	
	$( '#look_actions' ).click( function ( e ) {
		
		e.preventDefault();
		
		if ( acts_msgs )  { $( '#acts_msgs' ).hide(); acts_msgs = false; }
		
		else { $( '#acts_msgs' ).show(); acts_msgs = true; }
	});
	
	setTimeout ( function () { $( '#acts_msgs' ).hide() }, 3500 );
	
	$( '.tmp_hide_me' ).each( function()  {
	
		$( this ).hide();
	});
	
	$( '.click_me' ).click ( function() {
		
		dis_id = this.id;
		
		$( '.tmp_hide_me' ).each( function()  {
		
			if ( $( this ).data( 'show_key' ) == dis_id ) $( this ).toggle();
		});		
	});
		