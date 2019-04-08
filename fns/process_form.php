<?php	
	//Check to see if the form has been submitted and save the values before we go any further
	function process_form(){
		if( $_POST['cust_submitted'] == "yes" ):
			//var_dump($_POST); //#DEBUG
			//echo "FOO".get_the_id(); //#DEBUG
			foreach( $_POST as $key => $value ):
				update_post_meta(get_the_id(), $key, $value);
			endforeach;
		endif;
	}
	
	add_action('the_post','process_form');
?>