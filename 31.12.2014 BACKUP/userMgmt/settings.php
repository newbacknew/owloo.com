<?php
/*****************************************************************************************
 * Solid PHP User Management System                                                      *
 * Copyright 2012 Mark Eliasen (MrEliasen)                                               *
 *                                                                                       *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com                                                   *
 * Version: 1.3.1                                                                        *
 *****************************************************************************************/
 
require_once('system/initiater.php');
$site->restricted_page('login.php');
set_current_page('account');

//Mensajes
$mensajes = $site->retrive_pmlist_dav(true);

//Datos del usuario
$udata = $site->get_profile($site->uid, 'id');

$profile_data = $site->get_profile();

$site->template_show_header();
?>
    <div class="owloo_main owloo_main_content">

        <div class="row show-grid">
            <div class="span12">
                <h2 class="owloo_main_title_h1 owloo_align_left"><?php if($profile_data['user_username'] == $site->username){ ?> Mi perfil <?php }else{ ?>Perfil de <?=$profile_data['user_username']?><?php } ?></h2>
            </div>
        </div>
        
        <div class="row show-grid">
          
            <div class="span11">
                <div class="tabs-left">
                    <ul class="nav nav-tabs">
                        <li class="active"><a href="#profile" data-toggle="tab">Mi Perfil</a></li>
                        <li><a href="#message" data-toggle="tab"><div id="pm_new_count" class="badge badge-info">0</div> Mensaje<?php $nunNewMensaje = 0; $cont = 1; foreach($mensajes as $sms){ if($sms['status'] == 'unread') $nunNewMensaje++; $cont++; if($cont > 5) break; } if($nunNewMensaje > 1) echo 's';?></a></li>
                    </ul>
                    <div class="tab-content">
                        <!-- Profile -->
                        <div class="tab-pane active" id="profile">
                            <div class="owloo_profile_personal_data">
                                <?php echo $site->generateProfile(); ?>
                                <div>
                                    <label><b>Correo electrónico</b></label>
                                    <span><?=$udata['user_email']?></span>
                                </div>
                                <hr />
                                <div>
                                    <label><b>Tipo de plan</b></label>
                                    <span>
                                        <?php
                                            $data = array($_SESSION['udata']['membergroup']);
                                            $stmt = $site->sql->prepare('SELECT name, id FROM member_groups WHERE id = ?;');
                                            $stmt->execute($data);
                                                                
                                            foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $grps){
                                                echo $grps['name'];
                                            }
                                        ?>
                                    </span>
                                </div>
                                    
                                <form id="formLogin" action="" onsubmit="return false;">
                                    <?php 
                                    ## Check if the user is viewing their own profile, if not, show the buttons ##
                                    if($profile_data['user_id'] != $site->uid):
                                    ?>
                                        <hr />
                                        <?php
                                        // show the button only if the PM system is enabled
                                        if($site->config('pm_system')){
                                        ?>
                                        <div>
                                            <button class="btn btn btn-info" onclick="profile_newpm('<?php echo $profile_data['user_username']; ?>');">
                                                <i class="icon-envelope icon-white"></i>
                                                Send Private Message
                                            </button>
                                        <?php
                                        }
                                            
                                            // show the friend buttons only if the friend system is enabled
                                        if($site->config('friend_system')){
                                        ?>
                                            <button class="btn btn-success btn_add_friend <?php echo ($profile_data['user_friend'] == false ? '' : 'hidden'); ?>" name="<?php echo $profile_data['user_id']; ?>">
                                                <i class="icon-user icon-white"></i>
                                                Send Friend Invite
                                            </button>
                                            
                                            <button class="btn btn-danger btn_remove_friend <?php echo ($profile_data['user_friend'] == false ? 'hidden' : ''); ?>" name="<?php echo $profile_data['user_id']; ?>">
                                                <i class="icon-remove icon-white"></i>
                                                <?php echo ($profile_data['user_friend'] == 'pending' ? 'Cancel Friend Request' : 'Remove From Friends'); ?>
                                            </button>
                                        </div>
                                    <?php 
                                        }
                                    endif; 
                                    ?>
                                </form>
                            </div>
                        </div>
                        <!-- Message -->
                        <div class="tab-pane" id="message">
                            <div>
                                <div class="pm_new_count_content">
                                    <?php $cont = 1; foreach($mensajes as $sms){    ?>
                                    <div id="pm_new_count_item_<?=$sms['pm_id']?>" class="pm_new_count_item<?php if($sms['sender'] == 'owloo' && $sms['status'] != 'read') echo ' pm_new_count_admin';?>">
                                        <div><?=$sms['subject']?></div>
                                        <a class="btn btn-success pop_box ReadSelectedPM ReadSelectedPM_dav<?php if($sms['status'] == 'read') echo ' pm_new_count_read';?>" name="<?=$sms['pm_id']?>" href="#">Leer</a>
                                    </div>
                                    <?php $cont++; if($cont > 5) break; }   ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
<?php
$site->template_show_footer();