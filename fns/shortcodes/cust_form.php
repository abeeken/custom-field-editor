<?php
    //The form - wraps the whole thing in a tidy package
	function cust_form($atts, $content = null){
		cust_pre_render();

        extract(shortcode_atts(array(
			"user" => '',
            "lock_override" => false                    
		), $atts));
		
		$current_user = wp_get_current_user();

		$user_role = $current_user->roles[0];
		
		$form_lock = get_option('form_lock');
		$is_locked = false;
		
		if( is_user_logged_in() ):
			//Can the user edit the form
			//Is the global lock on?
			if($form_lock == "yes"){
				if($lock_override){
					//Nothing to do
				} else {
					$is_locked = true;
				}
			}
			
			if(!$is_locked){
				if($user == ""){
					$GLOBALS['cfe_can_edit'] = true;
				} else {
					$users = explode(",",$user);

					//var_dump($users); //#DEBUG     

					if (in_array($user_role, $users)){
						//echo "2"; //#DEBUG
						$GLOBALS['cfe_can_edit'] = true; 
					} else {
						//echo "3"; //#DEBUG
						$GLOBALS['cfe_can_edit'] = false;
					}
				}
			} else {
				$GLOBALS['cfe_can_edit'] = false;
			}			
				
				
				//echo "Is Locked:".$is_locked; //#DEBUG				

				//echo "Can Edit: ".$can_edit; //#DEBUG
				
				//echo "Session: ".$GLOBALS['cfe_can_edit']; //#DEBUG
		
			do_action( 'custom_field_editor_before_form_render' );
			
			//echo "<p>CAN EDIT: ".$GLOBALS['cfe_can_edit']."</p>"; // #DEBuG
		
			return '<form action="#" method="post" class="cust_form"><input type="hidden" name="cust_submitted" value="yes" />'.do_shortcode($content).'</form>';
		else:
			return do_shortcode($content);
		endif;		
	}
	
	add_shortcode("cust_form", "cust_form");
?>