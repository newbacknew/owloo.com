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

//Upload the avatar if the user click "Upload Avatar"
$site->process_uploadavatar(null, true);
//Update the settings or profile if either forms where submitted.
$site->admin_process_settings();
$site->admin_process_profile();

$udata = $site->get_profile($site->sanitize($_GET['uid'],'integer'), 'id');

$site->template_show_header();
?>
<div class="row show-grid">
    <div class="span12">
		<h2><?php echo $site->format_ending($udata['user_username']); ?> Profile &amp; Settings</h2>
	</div>
</div>

<div class="row show-grid">

    <div class="span3">
		<form class="profileform" id="formLogin2" action="" enctype="multipart/form-data" method="post">
			
			<div id="big_avatar" width="180" height="180">
				<img src="<?php echo (!empty($udata['user_avatar']) ? $udata['user_avatar'] : $site->base_url.'/images/default_big.png'); ?>" alt="avatar" />
			</div>
			
			<div>
				<input type="file" class="fileupload" name="avatar" /><br />
				<br />
				<button type="submit" class="btn btn-success">Upload Avatar</button> Or <a class="btn btn-info" id="useWebcam">Use Webcam</a>
			</div>
			
			<div id="webcam">
				<hr />
				<div> 
					<div class="screen"></div>
					<div class="buttonPane">
						<a class="btn btn-success" href="#" id="takePhoto">Take Picture</a> or <a class="btn btn-danger" href="#" id="closeWebcam">Close</a>
					</div>
					<div class="buttonPane" style="display:none;">
						<a class="btn btn-success" href="#" id="uploadAvatar">Upload Avartar</a> <a class="btn btn-danger" href="#" id="retakePhoto">Retake</a> 
					</div>
				</div>
				<hr />
			</div>
		   
			<input type="hidden" name="new_avatar" value="true" />
		</form>
	</div>
	
    <div class="span9">
	
		<div class="tabs-left">
			<ul class="nav nav-tabs">
				<li class="active"><a href="#account" data-toggle="tab"><i class="icon-cog"></i> Account</a></li>
				<li><a href="#profile" data-toggle="tab"><i class="icon-list"></i> Profile</a></li>
				<?php 
					if($site->config('paidmemberships')){
						echo '<li><a href="#memberships" data-toggle="tab"><i class="icon-list"></i> User Payments</a></li>';
					}
				?>
			</ul>

			<div class="tab-content">
			
				<!-- Account -->
				<div class="tab-pane active" id="account">
					<fieldset>
						<legend>Account Settings</legend>
						<form id="formLogin" action="#" class="form-inline">
						
						<div>	
							<label><b>Admin Password</b></label><br />
							To update this account's settings, you must provide your current password.<br />
							<input type="password" placeholder="Your Current Password" name="cur_password" />
						</div>
						
						<hr />
						
						<div>	
							<strong>Primary Group:</strong><br />
							<select name="primary_group">
								<?php
									foreach($site->getList('groups') as $id => $name){
										echo '<option value="'.$id.'" '.($id == $udata['user_primarygroup'] ? 'selected="selected"' : '').'>'.$name.'</option>';
									}
								?>
							</select>
							<br />
							<br />
							<strong>Account additional Groups:</strong><br />
							<?php
								foreach($site->getList('groups') as $id => $name){
									echo '<label class="checkbox">
											<input type="checkbox" name="additional_groups[]" value="'.$id.'" '.(in_array($id, $udata['user_othergroups']) ? 'checked="checked"' : '').'> '.$name.'
										  </label><br />';
								}
							?>
						</div>
						
						<hr />
						
						<div>
							<label><b>Update Password</b> <a href="#" data-rel="tooltip" data-title="leave blank for no change. Must be at least 6 characters long, and must not contain your username."><i class="icon-question-sign"></i></a></label><br />
							<input type="password" placeholder="New Password" name="upd_password" />
							<input type="password" placeholder="Confirm New Password" name="upd_password2" />
						</div>
						
						<hr />
						
						<div>
							<label><b>Reset Account Key <a href="#" data-rel="tooltip" data-title="By resetting your account key, all 'remember me' cookies bound to your account will be invalidated."><i class="icon-question-sign"></i></a>:</b> <input type="checkbox" name="reset_acc_key" /></label>
						</div>
						<hr />
						
						<div>
							<label><b>Update Email</b></label><br />
							<input type="text" name="upd_email" value="<?php echo $udata['user_email']; ?>" />
						</div>
						
						<hr />
						
						<div class="form-actions">
							<button type="submit" id="submit_account_changes"  class="btn btn-primary">Submit Changes</button>
						</div>
						<input type="hidden" name="upd_form" value="" />
						<input type="hidden" name="userid" value="<?php echo $site->sanitize($_GET['uid'],'integer'); ?>" />
					</form>
					</fieldset>
				</div>
				
				<!-- Account -->
				<div class="tab-pane" id="profile">
					<fieldset>
						<?php echo $site->showEditProfileFields(true); ?>
					</fieldset>
				</div>
				
			<?php
				if($site->config('paidmemberships')):
			?>
				<div class="tab-pane" id="memberships">
					<fieldset>
						<?php
							echo $site->showPurchases($site->sanitize($_REQUEST['uid'],'integer'));
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
<?php
$site->template_show_footer();