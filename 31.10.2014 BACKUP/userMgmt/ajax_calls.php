<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
define('AJAX_CALL', true);

if(!empty($_SERVER['HTTP_REFERE']) && strpos($_SERVER['HTTP_REFERER'], 'admin.php') !== false ){
	define('ADMIN_PANEL', true);
}

require_once('system/initiater.php');

$_REQUEST = array_merge($_GET, $_POST);

if(!empty($_REQUEST['call'])){
	switch($_REQUEST['call']){
		
		case 'forgotpass':
			sleep(1);
			$status = $site->process_sendResetPassword();
			break;
		
		case 'checkaccount':
			$status = $site->process_checkaccount();
			break;
		case 'search':
			$search = array('query'=>(!empty($_POST['query']) ? $site->sanitize($_POST['query'], 'string') : ''), 'srch_members'=>(!empty($_POST['sf_mbr']) ? true : false));
			$status = $site->process_search($search);
			break;
			
		case 'updaccount':
			$status = $site->process_settings();
			break;
		case 'updprofile':
			$status = $site->process_profile();
			break;
		case 'admupdprofile':
			$status = $site->admin_process_profile();
			break;
		
		case 'sendpm':
			$send = array('to_user'=>$_POST['sendto'], 'subject'=>urldecode($_POST['subject']), 'message'=>$_POST['message']);
			$status = $site->process_sendpm($send);
			break;
		case 'deletepm':
			$status = $site->process_deletepm($_POST['id']);
			break;
		case 'masspmdelete':
			$status = $site->process_massdeletepm($_POST['id']);
			break;
		case 'readpm':
			if(!empty($_POST['origen']))
				$status = $site->process_readpm($_POST['id'], $_POST['origen']);
			else
				$status = $site->process_readpm($_POST['id']);
			break;
		case 'pmlist':
			$status = $site->retrive_pmlist();
			break;
		case 'pmlist_dav':
			$status = $site->retrive_pmlist_dav(true);
			break;	
			
		case 'addfriend':
			$status = $site->process_sendrequest($_POST['fid']);
			break;
		case 'acceptfriend':
			$status = $site->process_acceptrequest($_POST['fid']);
			break;
		case 'removefriend':
			$status = $site->process_removefriend($_POST['fid']);
			break;
			
		case 'top_search':
			$status = $site->process_autosearch('users');
			break;
		case 'adm_membersgroups':
			$status = $site->process_autosearch('membergroups');
			break;
		case 'adm_members':
			$status = $site->process_autosearch('users', true);
			break;
		case 'adm_searchall':
			$status = $site->process_autosearch('all', true);
			break;
			
		case 'upload_avatar':
			$status = $site->process_uploadavatar(true);
			break;
		case 'get_avatar':
			sleep(2);
			$status = $site->refresh_avatar();
			break;
			
		case 'sendinvite':
			$status = $site->send_invite();
			break;
		case 'revokeinvite':
			$status = $site->revoke_invite();
			break;
			
			
		/* Administration */
		case 'adm_updgroup':
			$status['status'] = $site->modify_membergroup();
			break;
		case 'adm_adduser':
			$status = $site->process_adduser();
			break;
		case 'adm_deltuser':
			$status = $site->process_deleteuser();
			break;
		
		case 'adm_deletegroup':
			$status['status'] = $site->delete_usergroup();
			break;
		case 'adm_getgroup':
			$status = $site->get_group_settings();
			break;
		case 'adm_editgroup':
			$status = $site->update_usergroup();
			break;
		case 'adm_addgroup':
			$status = $site->process_addgroup();
			break;
			
		case 'adm_savesettings':
			$status['status'] = $site->saveSettings();
			break;
		case 'adm_loademailtmpl':
			$status = $site->loadEmailTemplate();
			break;
		case 'adm_saveemailtmpl':
			$status = $site->saveEmailTemplate();
			break;
		case 'adm_enviarmensajeall':
			$status = $site->enviarMensajeAll();
			break;
		case 'adm_loadEditLanguage':
			$status = $site->loadEditLanguage();
			break;
		case 'adm_savelanguage':
			$status = $site->saveLanguage();
			break;
		case 'adm_clearCache':
			$status['status'] = $site->clearCache();
			break;
		case 'adm_deleteProfileField'://check if the user is admin
			$status['status'] = $site->deleteProfileField();
			break;
		case 'adm_saveProfileFields':
			$status['status'] = $site->saveProfileFields();
			break;
		case 'adm_clearlog':
			$status['status'] = $errHandler->clearLog( (empty($site->permissions['admin']) || !$site->permissions['admin'] ? false : true) );
			break;
		case 'adm_checkversion':
			$status = $site->checkversion();
			break;
			
		case 'adm_give_user_invites':
			$status['status'] = $site->set_user_invites();
			break;
		case 'adm_give_group_invites':
			$status['status'] = $site->set_group_invites();
			break;
		case 'admin_process_settings':
			$status = $site->admin_process_settings();
			break;
			
		case 'adm_savesocialsettings':
			$status = $site->saveSocialAuth();
			break;
		case 'adm_activateacc':
			$status = $site->manualAccountActivation();
			break;
			
		//Forum
		case 'usrtooltip':
			$status = $site->process_tooltip();
			break;
		case 'newtopic':
			$status = $site->process_newtopic();
			break;
		case 'newreply':
			$status = $site->process_newreply();
			break;
		case 'editpost':
			$status = $site->process_editpost();
			break;
		case 'deletePost':
			$status = $site->process_editpost(true);
			break;
		case 'adm_saveforum':
			$status = $site->process_saveforum();
			break;
		case 'adm_deleteforum':
			$status = $site->process_deleteforum();
			break;
		case 'adm_loadcatsettings':
			$status = $site->process_loadcatsettings();
			break;
		case 'adm_savecatsettings':
			$status = $site->process_savecatsettings();
			break;
			
		// Triggers
		case 'adm_addtrigger':
			$status = $site->applyTriggers();
			break;
		case 'adm_removetrigger':
			$status = $site->removeTriggers();
			break;
			
		// Memberships
		case 'getlist':
			$status = $site->getList();
			break;
		case 'adm_deleteMembership':
			$status = $site->deleteMembership();
			break;
		case 'adm_savememberships':
			$status = $site->saveMembershipPlans();
			break;
	}
	
	echo json_encode($status);
	exit;
}