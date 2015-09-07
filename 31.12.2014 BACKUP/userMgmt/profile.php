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

/**** FAVORITES ****/
$user_profile = $site->get_profile();;
if(!empty($user_profile))
    set_current_user_id($user_profile['user_id']);
$_favorites = get_current_favorite_array();
$_country_favorites = array();
$_twitter_favorites = array();
$_fb_page_favorites = array();
foreach ($_favorites['country'] as $value) {
    $_aux_data = get_current_favorite_country_data($value);
    if(!empty($_aux_data))
        $_country_favorites[] = $_aux_data;
}
foreach ($_favorites['page'] as $value) {
    $_aux_data = get_current_favorite_page_data($value);
    if(!empty($_aux_data))
        $_fb_page_favorites[] = $_aux_data;
}
reconnect_db('owloo_twitter');
foreach ($_favorites['twitter'] as $value) {
    $_aux_data = get_current_favorite_twitter_data($value);
    if(!empty($_aux_data))
        $_twitter_favorites[] = $_aux_data;
}
/**** FIN FAVORITES ****/

//Upload the avatar if the user click "Upload Avatar"
$site->process_uploadavatar();
//Update the settings or profile if either forms where submitted.
$site->process_settings();
$site->process_profile();
$site->socialAuthLink();
$site->socialAuthUnLink();

//Mensajes
$mensajes = $site->retrive_pmlist_dav(true);
//Datos del usuario
$udata = $site->get_profile($site->uid, 'id');
$profile_data = $site->get_profile();

$udata = $site->get_profile($site->uid, 'id');
$site->template_show_header();
?>
<div class="owloo_main owloo_main_content">
    <div class="row show-grid">
        <div class="span12">
            <h2 class="owloo_main_title_h1 owloo_align_left">Herramientas disponibles</h2>
        </div>
    </div>
    <div class="row show-grid">
        
        <div class="span12">
        
            <div class="tabs-left">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#tools" data-toggle="tab">Monitoreo</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tools">
                        <div class="owloo_admin_tools">
                            <div class="owloo_title">Accede rápido a tus estadísticas. Puedes monitorear hasta 18 páginas.</div>
                            <div class="owloo_tools_no_favorites<?=((!empty($_country_favorites) || !empty($_twitter_favorites) || !empty($_fb_page_favorites))?' owloo_hide':'')?>">
                                <p>Monitorea uno o varios perfiles de Twitter, las estadísticas de un país especifico o una página de Facebook.</p>
                                <p>Encuentra este botón.</p>
                                <div class="owloo_favorite_icon"></div>
                            </div>
                            <?php if(!empty($_country_favorites) || !empty($_twitter_favorites) || !empty($_fb_page_favorites)){ ?>
                            <div class="owloo_tools_favorites">
                                <?php if(!empty($_twitter_favorites)){ ?>
                                <div class="owloo_tools_favorites_tw owloo_tools_favorites_twitter">
                                    <div class="owloo_title">Perfiles de Twitter que estoy monitoreando</div>
                                    <div class="owloo_tools_favorites_tw_content">
                                        <?php foreach ($_twitter_favorites as $value) { ?>
                                        <div class="owloo_tools_favorites_tw_item" id="twitter-<?=$value['id']?>">
                                            <a href="<?=URL_ROOT?>twitter-stats/userpage/<?=convert_to_url_string($value['name'])?>" target="_blank"><img src="<?=convert_imagen_to_https($value['image'])?>" /></a>
                                            <div>
                                                <a href="<?=URL_ROOT?>twitter-stats/userpage/<?=convert_to_url_string($value['name'])?>" target="_blank"><?=$value['nombre']?></a>
                                                <span class="owloo_delete_favorite" type="twitter" element="<?=$value['id']?>">Eliminar</span>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } if(!empty($_fb_page_favorites)){ ?>
                                <div class="owloo_tools_favorites_tw owloo_tools_favorites_page">
                                    <div class="owloo_title">Páginas de Facebook que estoy monitoreando</div>
                                    <div class="owloo_tools_favorites_tw_content">
                                        <?php foreach ($_fb_page_favorites as $value) { ?>
                                        <div class="owloo_tools_favorites_tw_item" id="page-<?=$value['id']?>">
                                            <a href="<?=URL_ROOT?>facebook-stats/pages/<?=strtolower($value['name'])?>/" target="_blank"><img src="<?=convert_imagen_to_https($value['image'])?>" /></a>
                                            <div>
                                                <a href="<?=URL_ROOT?>facebook-stats/pages/<?=strtolower($value['name'])?>/" target="_blank"><?=$value['nombre']?></a>
                                                <span class="owloo_delete_favorite" type="page" element="<?=$value['id']?>">Eliminar</span>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } if(!empty($_country_favorites)){ ?>
                                <div class="owloo_tools_favorites_tw owloo_tools_favorites_country">
                                    <div class="owloo_title">Países que estoy monitoreando</div>
                                    <div class="owloo_tools_favorites_tw_content">
                                        <?php foreach ($_country_favorites as $value) { ?>
                                        <div class="owloo_tools_favorites_tw_item" id="country-<?=$value['id']?>">
                                            <a class="owloo_country_flag" href="<?=URL_ROOT?>facebook-stats/<?=convert_to_url_string($value['name'])?>/" target="_blank"><img src="<?=URL_IMAGES?>flags/64/<?=$value['image']?>.png" align="" /></a>
                                            <div>
                                                <a href="<?=URL_ROOT?>facebook-stats/<?=convert_to_url_string($value['name'])?>/" target="_blank"><?=$value['nombre']?></a>
                                                <span class="owloo_delete_favorite" type="country" element="<?=$value['id']?>">Eliminar</span>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        $(document).ready(function(){
            var twitter_count = <?=count($_twitter_favorites)?>;
            var country_count = <?=count($_country_favorites)?>;
            var page_count = <?=count($_fb_page_favorites)?>;
            $('.owloo_delete_favorite').click(function(){
                var element = $(this).attr('type') + '-' + $(this).attr('element');
                var type = $(this).attr('type');
                $.post('<?=URL_ROOT?>ajax/check_favorite.php', {type: $(this).attr('type'), id_element: $(this).attr('element')}, function(res){
                    if(res == 0){
                        showNotification('Inicia sesión para monitorear esta página', '', 'error', false);
                    }else if(res == 2){
                        showNotification('Ahora ya estás monitoreando esta página', '', 'success', false);
                    }else if(res == 1 || res == 3 || res == 5){
                        showNotification('Ha ocurrido un error, Favor inténtelo más tarde.', '', 'error', false);
                    }else if(res == 4){
                        showNotification('Ahora ya no estás monitoreando esta página.', '', 'success', false);
                        $('#' + element).fadeOut(1000, function(){
                            if(type == 'twitter'){
                                if(--twitter_count < 1)
                                    $('.owloo_tools_favorites_twitter').css('display', 'none');
                            }
                            else if(type == 'page'){
                                if(--page_count < 1)
                                    $('.owloo_tools_favorites_page').css('display', 'none');
                            }
                            else if(type == 'country'){
                                if(--country_count < 1)
                                    $('.owloo_tools_favorites_country').css('display', 'none');
                            }
                            if(twitter_count < 1 && country_count < 1 && page_count < 1){
                                $('.owloo_tools_no_favorites').removeClass('owloo_hide');
                            }
                        });
                    }
                });
            });
        });
    </script>
    
    <div class="row show-grid">
        <div class="span12">
            <h2 class="owloo_main_title_h1 owloo_align_left">Configuración de perfil</h2>
        </div>
    </div>
    <div class="row show-grid">
        
        <div class="span12">
        
            <div class="tabs-left">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#profile" data-toggle="tab">Mi Perfil</a></li>
                    <li><a href="#account" data-toggle="tab">Cuenta</a></li>
                    <li><a href="#user" data-toggle="tab">Usuario</a></li>
                    <?php 
                    ## Show the invitation menu item if the invite system is enabled. ##
                    echo ($site->config('invite_system') ? '<li><a href="#invites" data-toggle="tab">Invites</a></li>' : '');
                    
                    ## Show the invitation menu item if the invite system is enabled. ##
                    echo ($site->config('paidmemberships') ? '<li><a href="#memberships" data-toggle="tab">Subscriptions</a></li>' : '');
                    
                    
                    $config = include(SYSTEM_PATH.'/hybridauth/config.php');
                    
                    if($config['active'] > 0){
                        echo '<li><a href="#social-connect" data-toggle="tab">Redes sociales</a></li>';
                    }
                    ?>
                    <?php if($site->config('invite_system')){ ?><li><a href="#message" data-toggle="tab"><div id="pm_new_count" class="badge badge-info">0</div> Mensaje<?php $nunNewMensaje = 0; $cont = 1; foreach($mensajes as $sms){ if($sms['status'] == 'unread') $nunNewMensaje++; $cont++; if($cont > 5) break; } if($nunNewMensaje > 1) echo 's';?></a></li><?php } ?>
                </ul>
    
                <div class="tab-content">
                 
                    <div class="tab-pane active" id="profile">
                        <?php if(!empty($udata['user_avatar'])){ ?><img src="<?=$udata['user_avatar']?>" /><?php } ?>
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
                                
                            <form id="formLogin1" action="" onsubmit="return false;">
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
                       
                    <!-- Account -->
                    <div class="tab-pane" id="account">
                        <fieldset>
                            <form id="formLogin" action="#" onsubmit="return false;">
            
                            <div>   
                                <label><b>Actualización del perfil</b></label>
                                <span>Para actualizar la configuración de su cuenta debes ingresar/proporcionar la contraseña actual.</span><br />
                                <input type="password" placeholder="Contraseña actual" name="cur_password" style="margin-top: 10px;" />
                            </div>
                            
                            <hr />
                            
                            <div>
                                <strong>Tipo de plan:</strong><br />
                                <select name="primary_group">
                                    <?php
                                        $groups = explode(',', $_SESSION['udata']['othergroups']);
                                        $groups[] = $_SESSION['udata']['membergroup'];
                                        
                                        $query = '';
                                        $data = array();
                                        foreach($groups as $gid){
                                            $query .= (empty($query) ? '?' : ',?');
                                            $data[] = $gid;
                                        }
                                        
                                        $stmt = $site->sql->prepare('SELECT name, id FROM member_groups WHERE id IN ('.$query.')');
                                        $stmt->execute($data);
                                        
                                        foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $grps){
                                            echo '<option value="'.$grps['id'].'" '.($grps['id'] == $_SESSION['udata']['membergroup'] ? 'selected="selected"' : '').'>'.$grps['name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                            
                            <hr />
                            
                            <div>
                                <label><b>Actualizar contraseña</b> <a href="#" data-rel="tooltip" data-title="Dejar en blanco si no deseas cambiar tu contraseña. Si desea cambiarla debe tener al menos 6 caracteres y no debe contener su nombre de usuario."><i class="icon-question-sign"></i></a></label>
                                <input type="password" placeholder="Contraseña nueva" name="upd_password" />
                                <input type="password" placeholder="Confirmar contraseña nueva" name="upd_password2" />
                            </div>
                            
                            <hr />
                            
                            <div style="position:absolute; left:-9999px;">
                            <div>
                                <label><b>Reset Account Key <a href="#" data-rel="tooltip" data-title="By resetting your account key, all 'remember me' cookies bound to your account will be invalidated."><i class="icon-question-sign"></i></a>:</b> <input type="checkbox" name="reset_acc_key" /></label>
                            </div>
                            <hr />
                            </div>
                            
                            <div>
                                <label><b>Actualizar correo electrónico</b></label>
                                <input type="text" name="upd_email" value="<?php echo $udata['user_email']; ?>" />
                            </div>
                            
                            <hr />
                            
                            <div class="form-actions" style="text-align:center;">
                                <button type="submit" id="submit_account_changes"  class="owloo_btn owloo_btn_blue">Confirmar Actualizaciones</button>
                            </div>
                            <input type="hidden" name="upd_form" value="" />
                        </form>
                        </fieldset>
                    </div>
                    
                    <!-- Account -->
                    <div class="tab-pane" id="user">
                        <fieldset>
                            <?php echo $site->showEditProfileFields(); ?>
                        </fieldset>
                    </div>
                    <?php if($site->config('invite_system')){ ?>
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
                    <?php } ?>
                    
                    <?php
                    ## Show the invitation tab if the invite system is enabled. ##
                    if($site->config('invite_system')): 
                    ?>
                        <!-- Invites -->
                        <div class="tab-pane" id="invites">
                            <div>
                                <form id="send_invite" class="form-inline" onsubmit="return false;">
                                    <legend>Send Invite <span id="noofinvites" class="badge badge-info"><?php echo $site->invites; ?></span></legend>
                                    <label>Sent To:</label> <input placeholder="E-mail" type="text" name="sendto" class="input-xlarge" />
                                    <button class="btn btn-success">Send Invite</button>
                                </form>
                            </div>
                            
                            <legend>Sent Invites</legend>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Sent To</th>
                                        <th>Sent Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        echo $site->showInvites();
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                    
                    <?php
                        if($config['active'] > 0):
                    ?>
                        <!-- Social Connect -->
                        <div class="tab-pane" id="social-connect">
                            <legend>Ingresa a owloo a través de tus redes sociales</legend>
                            <div class="row">
                                <?php
                                    echo $site->showProviders();
                                ?>
                            </div>
                        </div>
                    <?php
                        endif;
                        
                        if($site->config('paidmemberships')):
                    ?>
                        <!-- Social Connect -->
                        <div class="tab-pane" id="memberships">
                            <fieldset>
                                <legend>Subscriptions</legend>
                                <p><strong>Upgrading/Downgrading</strong><br />If you have an active Payment Plan when upgrading, downgrading or renewing, your current active plan will be converted prorata and extend the new plan you purchase. <strong>All sales are final, no refunds</strong>.</p>
                                <?php
                                    echo $site->showSubscriptions();
                                    echo $site->showPurchases();
                                ?>
                            </fieldset>
                        </div>
                    <?php
                        endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
$site->template_show_footer();