<?php
    //Initialise Global Variables used by this plugin
	function cfe_globals(){
		global $cfe_can_edit;
		
		$cfe_can_edit = "";
	}
	
	add_action( 'parse_query', 'cfe_globals' );
?>