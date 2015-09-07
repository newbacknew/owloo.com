<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
 
require_once('system/initiater.php');
$site->restricted_page('index.php', true);
	
$site->template_show_header();
?>
<div class="owloo_main owloo_main_content">
    <div class="row show-grid">
        <div class="span12">
    		<h1>Admin Panel</h1>
    		<p>Welcome to the Administration Panel.</p>
    		
    		<!-- Admin Panel Begin -->
    		<div class="tabs-left">
    			<ul class="nav nav-tabs">
    				<li class="active"><a href="#users" data-toggle="tab"><i class="icon-user"></i> Users</a></li>
    				<li><a href="#triggers" data-toggle="tab"><i class="icon-flag"></i> Triggers</a></li>
    				<li><a href="#usergroups" data-toggle="tab"><i class="icon-list"></i> Groups</a></li>
    				<li><a href="#profile-fields" data-toggle="tab"><i class="icon-list-alt"></i> Profiles</a></li>
    				<li><a href="#invitations" data-toggle="tab"><i class="icon-bullhorn"></i> Invitations</a></li>
    				<li><a href="#memberships" data-toggle="tab"><i class="icon-random"></i> Membership Plans</a>
    				<li><hr /></li>
    				<li><a href="#forum-forums" data-toggle="tab"><i class="icon-comment"></i> Forum Manager</a>
    				<li><hr /></li>
    				<li><a href="#social-auth" data-toggle="tab"><i class="icon-thumbs-up"></i> Social Integration</a>
    				<li><hr /></li>
    				<li><a href="#settings-general" data-toggle="tab"><i class="icon-cog"></i> Settings [General]</a>
    				<li><a href="#settings-timelang" data-toggle="tab"><i class="icon-cog"></i> Settings [Lang/Time]</a>
    				<li><a href="#settings-security" data-toggle="tab"><i class="icon-cog"></i> Settings [Security]</a>
    				<li><a href="#settings-account" data-toggle="tab"><i class="icon-cog"></i> Settings [Account]</a>
    				<li><a href="#settings-ipn" data-toggle="tab"><i class="icon-cog"></i> Settings [Memberships]</a>
    				<li><a href="#settings-invref" data-toggle="tab"><i class="icon-cog"></i> Settings [Invite/Referral]</a>
    				<li><a href="#settings-legal" data-toggle="tab"><i class="icon-cog"></i> Settings [Legal]</a>
    				<li><a href="#settings-forum" data-toggle="tab"><i class="icon-cog"></i> Settings [Forum]</a>
    				<li><hr /></li>
    				<li><a href="#emaileditor" data-toggle="tab"><i class="icon-envelope"></i> Email Templates</a>
    				<li><a href="#langeditor" data-toggle="tab"><i class="icon-globe"></i> Language Editor</a>
    				<li><hr /></li>
    				<li><a href="#error-log" data-toggle="tab"><i class="icon-exclamation-sign"></i> Error Log</a>
    				<li><a href="#version-check" data-toggle="tab"><i class="icon-search"></i> Version Check</a>
                    <li><hr /></li>
    				<li><a href="#envio-correo" data-toggle="tab"><i class="icon-envelope"></i> Envío de correos</a>
    			</ul>
    
    			<div class="tab-content">
    				<!-- Manage Users -->
    				<div class="tab-pane active" id="users">
    					<fieldset>
    						<legend>
    							User Management <button class="btn btn-info admShowAddUser"><i class="icon-plus-sign icon-white"></i> Add User</button>
    							<div></div>
    						</legend>
    						<div id="user_list">
    							<form id="adm_newuser" class="form-inline">
    								<table class="table table-bordered">
    									<tbody>
    										<tr>
    											<td><input placeholder="Username" type="text" name="username" class="input-small" /></td>
    											<td>
    												<input placeholder="Password" type="password" name="password" class="input-small" />
    												<a href="#" data-rel="tooltip" data-title="Leave the password blank to let the system generate a password for the account."><i class="icon-question-sign"></i></a>
    											</td>
    											<td><input placeholder="E-mail" type="text" name="email" class="input-small" /></td>
    											<td>
    												<select name="usergroup">
    													<?php
    													$groups = $site->get_membergroups();
    													
    													if(!empty($groups)){
    														foreach($groups as $row){
    															echo '<option value="'.$row['id'].'##'.$row['name'].'">'.$row['name'].'</option>';
    														}
    													}
    													?>
    												</select>
    											</td>
    											<td>
    												<label class="checkbox">
    													<input type="checkbox" name="sendemail"> Email Login Info 
    													<a href="#" data-rel="tooltip" data-title="When the account is created, the password and login information will be emailed to the new user."><i class="icon-question-sign"></i></a>
    												</label>
    											</td>
    											<td>
    												<button class="adm_newuser_add btn btn-success">Create User</button>
    											</td>
    										</tr>
    									</tbody>
    								</table>
    							</form>
    							
    							<table class="table user_list">
    								<thead>
    									<tr>
    										<th><a href="?sort=id&amp;list=<?php echo (!empty($_GET['list']) && $_GET['list'] == 'asc' ? 'desc' : 'asc'); ?>">ID</a></th>
    										<th><a href="?sort=username&amp;list=<?php echo (!empty($_GET['list']) && $_GET['list'] == 'asc' ? 'desc' : 'asc'); ?>">Username</a></th>
    										<th>Email</th>
    										<th><a href="?sort=membergroup&amp;list=<?php echo (!empty($_GET['list']) && $_GET['list'] == 'asc' ? 'desc' : 'asc'); ?>">Member Group</a></th>
    										<th>Last Activity</th>
    									</tr>
    								</thead>
    								<tbody>
    									<?php
    										$userlist = $site->generate_userlist();
    										echo $userlist['users'];
    									?>
    								</tbody>
    							</table>
    							<div id="ul_pagination" class="pagination"><?php echo $userlist['pagination']; ?></div>
    						</div>
    					</fieldset>
    				</div>
    				
    				<!-- Manage Member Groups -->
    				<div class="tab-pane" id="usergroups">
    					<fieldset>
    						<legend>Member Groups <button id="show_adm_newgroup" class="btn btn-info"><i class="icon-plus-sign icon-white"></i> Add Group</button></legend>
    						<div id="group_list">
    							<table class="table group_list">
    								<thead>
    									<tr>
    										<th>ID</th>
    										<th>Name</th>
    										<th></th>
    										<th></th>
    									</tr>
    								</thead>
    								<tbody>
    									<?php
    										$grouplist = $site->generate_grouplist();
    										echo $grouplist['groups'];
    									?>
    								</tbody>
    							</table>
    							<div id="mg_pagination" class="pagination"><?php echo $grouplist['pagination']; ?></div>
    						</div>
    					</fieldset>
    				</div>
    				
    				<!-- Manage Inviations -->
    				<div class="tab-pane" id="invitations">
    					<fieldset>
    						<legend>Invitations</legend>
    						
    						<form id="manage_user_invites" class="form-inline">
    							<select name="action" class="input-small">
    								<option value="add">Give</option>
    								<option value="remove">Take</option>
    							</select>
    							<input type="number" min="1" class="input-mini" name="invites" value="1" />
    							invites to/from <strong>user</strong> 
    							<input type="text" class="input-medium" name="username" placeholder="username" autocomplete="off">
    							<button type="submit" class="btn btn-success">Submit</button>
    						</form>
    						
    						<form id="manage_group_invites" class="form-inline">
    							<select name="action" class="input-small">
    								<option value="add">Give</option>
    								<option value="remove">Take</option>
    							</select>
    							<input type="number" min="1" class="input-mini" name="invites" value="1" />
    							invites to/from the <strong>group</strong> 
    							<input type="text" class="input-medium" name="group" placeholder="group name" autocomplete="off">
    							<button type="submit" class="btn btn-success">Submit</button>
    						</form>
    						
    						<legend>Latest Invites</legend>
    						<p>Displaying the 20 latest invites sent by members.</p>
    						<table class="table">
    							<thead>
    								<tr>
    									<th>Sent By</th>
    									<th>Sent To</th>
    									<th>Status</th>
    									<th>Date</th>
    								</tr>
    							</thead>
    							<tbody>
    								<?php echo $site->show_latestinvites(); ?>
    							</tbody>
    						</table>
    						
    					</fieldset>
    				</div>
    				
    				<!-- Manage Paid Memberships -->
    				<div class="tab-pane" id="settings-ipn">
    					<fieldset>
    						<form id="adm_paypal_settings" class="form-horizontal" action="admin.php" method="post">
    							<legend>Paid Memberships <button class="btn btn-success btn_savesettings"><i class="icon-arrow-right icon-white"></i> Save Settings</button></legend>
    							<?php echo $site->subscriptionSettings(); ?>
    							
    							<legend>PayPal IPN Settings</legend>
    							<p>Paid Memberships currently only support PayPal. The buttons contains the IPN url already, making all IPN callbacks associated with the buttons point to this URL: <code><?php echo $site->base_url.'/system/ipn.php'; ?></code>. So you do not need to modify your IPN settings in your PayPal account.<br />
    							If you want to change this URL (eg. if you change your IPN receiver file location), please see the "generateButtons" function in class_memberships.php.</p>
    							
    							<p><strong>Known Issues:</strong> <a href="https://github.com/Quixotix/PHP-PayPal-IPN#known-issues">Click here for fixes and answers</a> to the few common issues you might encounter when configuring your IPN receiver.</p>
    							<p><strong>NOTE:</strong> It is highly recommended that you test your settings with a PayPal Developer Account/Sandbox account before making it live on your site.</p>
    							<br />
    							
    							<?php echo $site->paypalSettings(); ?>
    						</form>
    						
    					</fieldset>
    				</div>
    				
    				<!-- Manage Paid Memberships -->
    				<div class="tab-pane" id="memberships">
    					<form id="adm_memberships" class="form-horizontal" action="admin.php" method="post" onsubmit="return false;">
    						<fieldset>
    							<?php echo $site->loadMemberships(); ?>
    						</fieldset>
    						<button id="adm_savememberships" class="btn btn-success">Save Membership Plans</button>
    					</form>
    				</div>
    				
    				<!-- System Configuration (General Settings) -->
    				<div class="tab-pane" id="profile-fields">
    					<form id="adm_profile_fields" class="form-horizontal" action="admin.php" method="post" onsubmit="return false;">
    						<fieldset>
    							<?php echo $site->loadProfileFields(); ?>
    						</fieldset>
    						<button class="btn btn-success adm_saveProfileFields">Save Profile Fields</button>
    					</form>
    				</div>
    				
    				<!-- System Configuration (General Settings) -->
    				<div class="tab-pane" id="settings-general">
    					<form id="adm_settings_general" class="form-horizontal" action="admin.php" method="post">
    						<fieldset>
    							<?php echo $site->loadConfigSettings('general'); ?>
    						</fieldset>
    					</form>
    				</div>
    				
    				<!-- System Configuration (Date and Lang Settings) -->
    				<div class="tab-pane" id="settings-timelang">
    					<form id="adm_settings_timelang" class="form-horizontal" action="admin.php" method="post">
    						<fieldset>
    							<?php echo $site->loadConfigSettings('langtime'); ?>
    						</fieldset>
    					</form>
    				</div>
    				
    				<!-- System Configuration (Security Settings) -->
    				<div class="tab-pane" id="settings-security">
    					<form id="adm_settings_security" class="form-horizontal" action="admin.php" method="post">
    						<fieldset>
    							<?php echo $site->loadConfigSettings('security'); ?>
    						</fieldset>
    					</form>
    				</div>
    				
    				<!-- System Configuration (Account Settings) -->
    				<div class="tab-pane" id="settings-account">
    					<form id="adm_settings_account" class="form-horizontal" action="admin.php" method="post">
    						<fieldset>
    							<?php echo $site->loadConfigSettings('accounts'); ?>
    						</fieldset>
    					</form>
    				</div>
    				
    				<!-- System Configuration (invite referral Settings) -->
    				<div class="tab-pane" id="settings-invref">
    					<form id="adm_settings_invref" class="form-horizontal" action="admin.php" method="post">
    						<fieldset>
    							<?php echo $site->loadConfigSettings('invitereferral'); ?>
    						</fieldset>
    					</form>
    				</div>
    				
    				<!-- System Configuration (Legal Settings) -->
    				<div class="tab-pane" id="settings-legal">
    					<form id="adm_settings_legal" class="form-horizontal" action="admin.php" method="post">
    						<fieldset>
    							<?php echo $site->loadConfigSettings('legal'); ?>
    						</fieldset>
    					</form>
    				</div>
    				
    				<!-- Forum Configuration (Legal Settings) -->
    				<div class="tab-pane" id="settings-forum">
    					<form id="adm_settings_forum" class="form-horizontal" action="admin.php" method="post">
    						<fieldset>
    							<?php echo $site->loadConfigSettings('forum'); ?>
    						</fieldset>
    					</form>
    				</div>
    				
    				<!-- - - - - - - - - - - - - - - - - - - - - - - - - - -  -->
    				
    				<!-- Account Triggers -->
    				<div class="tab-pane" id="triggers">
    					<fieldset>
    						<?php echo $site->triggerActions(); ?>
    					</fieldset>
    				</div>
    				
    				<!-- Social Integration -->
    				<div class="tab-pane" id="social-auth">
    					<fieldset>
    						<?php echo $site->socialAuthAdm(); ?>
    					</fieldset>
    				</div>
    				
    				<!-- Email Template Editor -->
    				<div class="tab-pane" id="emaileditor">
    					<fieldset>
    						<?php echo $site->emailTemplateList(); ?>
    						<form id="emailtemplate" class="form-horizontal" action="#" method="post" onsubmit="return false;">
    							<p>Please select a template from the above drop down.</p>
    						</form>
    					</fieldset>
    				</div>
                    
                    <!-- Envío de correos a los usuarios -->
                    <div class="tab-pane" id="envio-correo">
    					<fieldset>
    						<legend>Envío de correos</legend>
    						<form id="envio-emails" class="form-horizontal" action="#" method="post" onsubmit="return false;">
    							<label for="dav_to">Para: </label><input type="text" name="dav_to" id="dav_to" /> <input type="checkbox" name="dav_enviarAll" id="dav_enviarAll" /><label for="dav_enviarAll">Enviar a todos los usuarios básicos</label>
                                <label for="dav_subject">Asunto: </label><input type="tel" name="dav_subject" id="dav_subject" />
                                <label for="dav_message_title">Título del mensaje: </label><input type="tel" name="dav_message_title" id="dav_message_title" />
                                <label for="dav_message">Mensaje: </label><textarea rows="12" name="dav_message" id="dav_message"></textarea><br/><br/>
                                <button class="btn btn-success enviarMensjeAll">Enviar Email</button>
    						</form>
    					</fieldset>
    				</div>
                    
    				<!-- Language Editor -->
    				<div class="tab-pane" id="langeditor">
    					<fieldset>
    						<?php echo $site->languageList(); ?>
    						<form id="languageeditor" class="form-horizontal" action="#" method="post" onsubmit="return false;">
    							<p>Please select a language from the above drop down.</p>
    						</form>
    					</fieldset>
    				</div>
    				
    				<!-- System Error Log -->
    				<div class="tab-pane" id="error-log">
    					<h2>System Error Log</h2>
    					<?php echo $errHandler->showErrLog(); ?>
    					<br />
    					<button id="clearErrLog" class="btn btn-warning">Clear Error Log</button>
    				</div>
    				
    				<!-- Version Checker -->
    				<div class="tab-pane" id="version-check">
    					<h2>Version Information</h2>
    					<strong>System Version:</strong> <span class="label"><?php echo $site->config('version');?></span><br />
    					<strong>Latest Version:</strong> <span id="latestversion" class="label label-success"> - - - </span><br />
    					<br />
    					<strong>Status:</strong> <span id="checkmessage">Check not running.</span>
    					<hr />
    					<button class="btn btn-primary">Check For Updates</button>
    				</div>
    				
    				
    				<!-- Forum Manager -->
    				<div class="tab-pane" id="forum-forums">
    					<form id="forum-manager" class="form-horizontal" action="admin.php" method="post" onsubmit="return false;">
    						<fieldset>
    							<?php echo $site->loadForumManager(); ?>
    						</fieldset>
    					</form>
    				</div>
    				
    			</div>
    		</div>
    		
    		<!-- Add New Group Modal -->
    		<div class="modal" id="AddNewGroup" tabindex="-1">
    			<div class="modal-header">
    				<h3 id="addNewGroup">New User Group</h3>
    			</div>
    			<div class="modal-body">
    				<form class="form-horizontal">
    				
    					<div class="control-group">
    						<label class="control-label" for="groupname">Group Name</label>
    						<div class="controls">
    							<input type="text" name="groupname" id="groupname" class="input-medium">
    						</div>
    					</div>
    					<div class="control-group">
    						<label class="control-label" for="groupname">Group Colour</label>
    						<div class="controls">
    							<input type="text" name="groupcolour" value="#123456" class="groupcolour input-small">
    							<div class="colorpicker editcolorpicker"></div>
    						</div>
    					</div>
    					
    					<legend>PM Permissions</legend>
    					<div class="control-group">
    						<label class="control-label" for="">
    							Read PMs 
    							<a href="#" data-rel="tooltip" data-title="Is the group allowed to read PMs?"><i class="icon-question-sign"></i></a>
    						</label>
    						<div class="controls">
    							<input type="checkbox" name="perm_readpm" value="on">
    						</div>
    					</div>
    					<div class="control-group">
    						<label class="control-label" for="">
    							Send PMs 
    							<a href="#" data-rel="tooltip" data-title="Is the group allowed to send PMs?"><i class="icon-question-sign"></i></a>
    						</label>
    						<div class="controls">
    							<input type="checkbox" name="perm_sendpm" value="on">
    						</div>
    					</div>
    					<div class="control-group">
    						<label class="control-label" for="">Inbox Limit</label>
    						<div class="controls">
    							<input type="number" min="10" class="input-mini" name="perm_limitpm" value="50">
    						</div>
    					</div>
    					
    					<legend>Friend Permissions</legend>
    					<div class="control-group">
    						<label class="control-label" for="">
    							View List
    							<a href="#" data-rel="tooltip" data-title="Is the group allowed to view their friendlist and requests?"><i class="icon-question-sign"></i></a>
    						</label>
    						<div class="controls">
    							<input type="checkbox" name="perm_viewfriend" value="on">
    						</div>
    					</div>
    					<div class="control-group">
    						<label class="control-label" for="">
    							Send Requests 
    							<a href="#" data-rel="tooltip" data-title="Is the group allowed to send friend requests?"><i class="icon-question-sign"></i></a>
    						</label>
    						<div class="controls">
    							<input type="checkbox" name="perm_sendfriend" value="on">
    						</div>
    					</div>
    					
    					<legend>Account Permissions</legend>
    					<div class="control-group">
    						<label class="control-label" for="">
    							Edit Settings
    							<a href="#" data-rel="tooltip" data-title="Is the group allowed to edit their account details?"><i class="icon-question-sign"></i></a>
    						</label>
    						<div class="controls">
    							<input type="checkbox" name="perm_accedit" value="on">
    						</div>
    					</div>
    					<div class="control-group">
    						<label class="control-label" for="">
    							Delete Account
    							<a href="#" data-rel="tooltip" data-title="Is the group allowed to delete their account?"><i class="icon-question-sign"></i></a>
    						</label>
    						<div class="controls">
    							<input type="checkbox" name="perm_accdel" value="on">
    						</div>
    					</div>
    					<div class="control-group">
    						<label class="control-label" for="">
    							View Level
    							<a href="#" data-rel="tooltip" data-title="This is used when protecting/restricting pages or a minimum required contect to view level. The higher value, the higher view level."><i class="icon-question-sign"></i></a>
    						</label>
    						<div class="controls">
    							<input type="number" min="1" class="input-mini" name="perm_viewlevel" value="1" />
    						</div>
    					</div>
    					<div class="control-group">
    						<label class="control-label" for="">
    							Admin Group
    							<a href="#" data-rel="tooltip" data-title="Give this group admin permissions?"><i class="icon-question-sign"></i></a>
    						</label>
    						<div class="controls">
    							<input type="checkbox" name="perm_accadmin" value="on">
    						</div>
    					</div>
    								
    					<legend>Invite Permissions</legend>
    					<div class="control-group">
    						<label class="control-label" for="">
    							Send Invites
    							<a href="#" data-rel="tooltip" data-title="Is the group allowed to send invitations?"><i class="icon-question-sign"></i></a>
    						</label>
    						<div class="controls">
    							<input type="checkbox" name="perm_invsend" value="on">
    						</div>
    					</div>
    					<div class="control-group">
    						<label class="control-label" for="">
    							Revoke Invites
    							<a href="#" data-rel="tooltip" data-title="Is the group allowed to delete/revoke un-accepted invitations?"><i class="icon-question-sign"></i></a>
    						</label>
    						<div class="controls">
    							<input type="checkbox" name="perm_invrevoke" value="on">
    						</div>
    					</div>
    				</form>
    			</div>
    			<div class="modal-footer">
    				<button id="close_adm_newgroup" class="btn">Close</button>
    				<button id="adm_newgroup" class="btn btn-primary">Add Group</button>
    			</div>
    		</div>
    		
    		<!-- Edit Group Modal -->
    		<div class="modal" id="EditGroup" tabindex="-1">
    			<div class="modal-header">
    				<h3 id="addNewGroup">New User Group</h3>
    			</div>
    			<div class="modal-body">
    				<form class="form-horizontal">
    				
    					<fieldset>
    						<legend>Friend Settings <button class="btn btn-success btn_savesettings"><i class="icon-arrow-right icon-white"></i> Save Settings</button></legend>
    						<div class="control-group">
    							<label class="control-label" for="settings_enablefriendsystem">
    								Name
    							</label>
    							<div class="controls">
    								<input type="text" class="input-xlarge" name="forum_cat_name" value="Category Name Here">
    							</div>
    						</div>
    						
    						<hr />
    						
    						<div class="control-group">
    							<label class="control-label" for="settings_enablefriendsystem">
    								View Permissions
    							</label>
    							<div class="controls">
    								<select multiple="multiple">
    									<option>Admin</option>
    									<option>Member</option>
    									<option>VIP</option>
    								</select>
    							</div>
    						</div>
    						
    						<div class="control-group">
    							<label class="control-label" for="settings_enablefriendsystem">
    								Topic Permissions
    							</label>
    							<div class="controls">
    								<select multiple="multiple">
    									<option>Admin</option>
    									<option>Member</option>
    									<option>VIP</option>
    								</select>
    							</div>
    						</div>
    						
    						<div class="control-group">
    							<label class="control-label" for="settings_enablefriendsystem">
    								Reply Permissions
    							</label>
    							<div class="controls">
    								<select multiple="multiple">
    									<option>Admin</option>
    									<option>Member</option>
    									<option>VIP</option>
    								</select>
    							</div>
    						</div>
    					</fieldset>
    					
    				</form>
    			</div>
    			<div class="modal-footer">
    				<button id="close_adm_editgroup" class="btn">Close</button>
    				<button id="adm_update_usergroup" class="btn btn-primary">Submit Changes</button>
    			</div>
    		</div>
    		
    		
    		<!-- Forum Category Settings  -->
    		<div class="modal" id="forum_cat_settings" tabindex="-1">
    			<div class="modal-body">
    				<form class="form-horizontal" action="#"></form>
    			</div>
    			<div class="modal-footer">
    				<button id="forum_catset_cancel" class="pull-left btn btn-danger">Cancel</button>
    				<button id="forum_catset_save" class="pull-right btn btn-success">Save Settings</button>
    			</div>
    		</div>
    		
    		<!-- Admin Panel End -->
    	</div>
    </div>
</div>
</div>

<script src="assets/farbtastic/farbtastic.js"></script>
<script src="assets/nestable/jquery.nestable.js"></script>
<script src="js/admin.js"></script>

<link rel="stylesheet" type="text/css" href="assets/farbtastic/farbtastic.css"/>
<?php
$site->template_show_footer();