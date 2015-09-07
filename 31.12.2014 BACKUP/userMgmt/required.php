<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.4.0 																		 *
 *****************************************************************************************/
require_once('system/initiater.php');
$site->restricted_page('login.php');
$site->process_triggers();
set_current_page('account');

// We call it up here to be able to set the required CSRF tokens before the first headers are sent
$form = $site->showTriggerEventsForm();

$site->template_show_header();
?>
<div class="owloo_main owloo_main_content">
    
    <?php //print_r($_SESSION); ?>
    
    <div class="row show-grid">
        <div class="span12">
            <div class="owloo_user_login_forgot">
                <h3>Datos del usuario</h3>
                <h6>Ya falta poco! Favor introduzca los siguientes datos:</h6>
            </div>
    		<?php 
    			echo $site->showNotification('required');
    			echo $form; 
    		?>
    	</div>
    </div>
</div>
</div>
<?php
$site->template_show_footer();