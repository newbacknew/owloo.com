<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/

if(!defined('IN_SYSTEM')){
	exit;
}

class Memberships extends UserSystem{
	public $currencies = array(
							'AUD' => array('name' => 'Australian Dollar', 'symbol' => '$'),
							'CAD' => array('name' => 'Canadian Dollar', 'symbol' => '$'),
							'CZK' => array('name' => 'Czech Koruna', 'symbol' => 'KÄ'),
							'DKK' => array('name' => 'Danish Krone', 'symbol' => 'Kr'),
							'EUR' => array('name' => 'Euro', 'symbol' => 'â‚¬'),
							'HKD' => array('name' => 'Hong Kong Dollar', 'symbol' => '$'),
							'HUF' => array('name' => 'Hungarian Forint', 'symbol' => 'Ft'),
							'ILS' => array('name' => 'Israeli New Sheqel', 'symbol' => 'â‚ª'),
							'JPY' => array('name' => 'Japanese Yen', 'symbol' => 'Â¥'),
							'MXN' => array('name' => 'Mexican Peso', 'symbol' => '$'),
							'NOK' => array('name' => 'Norwegian Krone', 'symbol' => 'Kr'),
							'NZD' => array('name' => 'New Zealand Dollar', 'symbol' => '$'),
							'PHP' => array('name' => 'Philippine Peso', 'symbol' => 'â‚±'),
							'PLN' => array('name' => 'Polish Zloty', 'symbol' => 'zÅ‚'),
							'GBP' => array('name' => 'British Pound', 'symbol' => 'Â£'),
							'SGD' => array('name' => 'Singapore Dollar', 'symbol' => '$'),
							'SEK' => array('name' => 'Swedish Krona', 'symbol' => 'kr'),
							'CHF' => array('name' => 'Swiss Franc', 'symbol' => 'SFr'),
							'TWD' => array('name' => 'New Taiwan Dollar', 'symbol' => 'è‡ºå¹£ / å°å¹£'),
							'THB' => array('name' => 'Thai Baht', 'symbol' => 'à¸¿'),
							'USD' => array('name' => 'U.S. Dollar', 'symbol' => '$')
						);
	
	public function subscriptionSettings(){
		$output = '<div class="control-group">
						<label class="control-label" for="settings_paidmemberships">
							Enable Memberships
							<a href="#" data-rel="tooltip" data-title="This will allow users to subscribe to paid memberships via thier profile page."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="checkbox" id="settings_paidmemberships" name="settings_paidmemberships" '.($this->config['paidmemberships'] ? 'checked="checked"' : '').' />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_crontype">
							Expiry Check Type
							<a href="#" data-rel="tooltip" data-title="&quot;Cron&quot; means you will manually add a cron job to take care of this. PHP means it will check as the user browsers the site Cron is less resource hungry if you have many users."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<select name="settings_crontype">
								<option value="0" '.(!$this->config['crontype'] ? 'selected="selected"' : '').'>PHP</option>
								<option value="1" '.($this->config['crontype'] ? 'selected="selected"' : '').'>Cron</option>
							</select><br />
							CronJob URL: <code>'.$this->base_url.'/system/cron.php?token='.(empty($this->config['crontoken']) ? 'YourTokenHere' : $this->config['crontoken']).'</code>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_crontoken">
							Cron Token
							<a href="#" data-rel="tooltip" data-title="(Cron Job Only) To keep unwanted users to run the cronjob, please specify a token which will work a bit like a password. Remember to add the token to the end of the cron job url."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="text" id="settings_crontoken" name="settings_crontoken" value="'.$this->config['crontoken'].'" />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_cronintval">
							PHP Cron Intval
							<a href="#" data-rel="tooltip" data-title="(PHP Cron Only) This is the time in seconds between checks for users. It is recommended to keep this above 300 seconds."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="number" min="60" class="input-mini" name="settings_cronintval" value="'.$this->config['cronintval'].'">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_crondebug">
							Cron Loggin
							<a href="#" data-rel="tooltip" data-title="If you are using Cronjobs to handle expiry checks, you can choose to log when the cronjob runs."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="checkbox" id="settings_crondebug" name="settings_crondebug" '.($this->config['crondebug'] ? 'checked="checked"' : '').' />
						</div>
					</div>';
		
		return $output;
	}
	
	public function paypalSettings(){
		$output = '<div class="control-group">
						<label class="control-label" for="settings_ipn_usecurl">
							CURL Post Backs
							<a href="#" data-rel="tooltip" data-title="If enabled, the recommended cURL PHP library is used to send the post back to PayPal. If disabled, fsockopen() is used."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="checkbox" id="settings_ipn_usecurl" name="settings_ipn_usecurl" '.($this->config['ipn_usecurl'] ? 'checked="checked"' : '').' />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_ipn_forcessl3">
							Force SSL-3
							<a href="#" data-rel="tooltip" data-title="If enabled, explicitly sets cURL to use SSL version 3. Use this if cURL is compiled with GnuTLS SSL"><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="checkbox" id="settings_ipn_forcessl3" name="settings_ipn_forcessl3" '.($this->config['ipn_forcessl3'] ? 'checked="checked"' : '').' />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_ipn_usessl">
							USE SSL
							<a href="#" data-rel="tooltip" data-title="If enabled, an SSL secure connection (port 443) is used for the post back as recommended by PayPal. If disabled, a standard HTTP (port 80) connection is used."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="checkbox" id="settings_ipn_usessl" name="settings_ipn_usessl" '.($this->config['ipn_usessl'] ? 'checked="checked"' : '').' />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_ipn_followloc">
							Curl Follow Location
							<a href="#" data-rel="tooltip" data-title="If true, cURL will use the CURLOPT_FOLLOWLOCATION to follow any &quot;Location: ...&quot; headers in the response."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="checkbox" id="settings_ipn_followloc" name="settings_ipn_followloc" '.($this->config['ipn_followloc'] ? 'checked="checked"' : '').' />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_ipn_sandbox">
							Enable Sandbox
							<a href="#" data-rel="tooltip" data-title="If enabled, the paypal sandbox URI www.sandbox.paypal.com is used for the post back. If disabled, the live URI www.paypal.com is used."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="checkbox" id="settings_ipn_sandbox" name="settings_ipn_sandbox" '.($this->config['ipn_sandbox'] ? 'checked="checked"' : '').' />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_ipn_timeout">
							Timeout
							<a href="#" data-rel="tooltip" data-title="The amount of time, in seconds, to wait for the PayPal server to respond before timing out"><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="number" min="10" class="input-mini" id="settings_ipn_timeout" name="settings_ipn_timeout" value="'.$this->config['ipn_timeout'].'" placeholder="30">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_ipn_debug">
							IPN Logging
							<a href="#" data-rel="tooltip" data-title="If enabled, all (valid and invalid) IPNs will be logged to the error log. This can take up a lot of disc space quickly and is only recommended to enable while debugging."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="checkbox" id="settings_ipn_debug" name="settings_ipn_debug" '.($this->config['ipn_debug'] ? 'checked="checked"' : '').' />
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_ipn_timeout">
							Currency Type
							<a href="#" data-rel="tooltip" data-title="The type of curreny used for your paid membership plans"><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<select name="settings_ipn_currency">';
								
								foreach($this->currencies as $name => $vals){
									$output .= '<option '.($this->config['ipn_currency'] == $name ? 'selected="selected"' : '').' value="'.$name.'">'.$vals['name'].(!empty($vals['symbol']) ? ' ('.$vals['symbol'].')' : '').'</option>';
								}
								
				$output .= '</select>
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_ipn_email">
							PayPal E-mail
							<a href="#" data-rel="tooltip" data-title="Payments will be made to the PayPal account linked to this email. WARNING: Make sure this email is for the same account as the API."><i class="icon-question-sign"></i></a>
						</label>
						<div class="controls">
							<input type="text" class="input-large" id="settings_ipn_email" name="settings_ipn_email" value="'.$this->config['ipn_email'].'" placeholder="your.paypal.email@domain.com">
						</div>
					</div>
					
					<legend>PayPal API Details</legend>
					<p><strong>Getting your API details</strong><br />
					1) If you haven\'t already, you will need to create a Paypal account.<br />
					2) Enable API access on your Paypal account via: My Account > Profile > API Access > Request API credentials > Request API Signature.<br />
					3) Insert the Paypal API username, password, and signature values below</p>
					<br />
					<div class="control-group">
						<label class="control-label" for="settings_pp_api_user">
							API User
						</label>
						<div class="controls">
							<input type="text" class="input-large" id="settings_pp_api_user" name="settings_pp_api_user" value="'.$this->config['pp_api_user'].'">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_pp_api_pass">
							API Password
						</label>
						<div class="controls">
							<input type="text" class="input-large" id="settings_pp_api_pass" name="settings_pp_api_pass" value="'.$this->config['pp_api_pass'].'">
						</div>
					</div>
					<div class="control-group">
						<label class="control-label" for="settings_pp_api_sig">
							API Signature
						</label>
						<div class="controls">
							<input type="text" class="input-large" id="settings_pp_api_sig" name="settings_pp_api_sig" value="'.$this->config['pp_api_sig'].'">
						</div>
					</div>';
		
		return $output;
	}
	
	public function loadMemberships(){
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		$plans = $this->sql->query('SELECT 
										membership_plans.*,
										member_groups.name,
										(SELECT COUNT(id) FROM members WHERE subscription_id = membership_plans.ms_id) as total
									FROM 
										membership_plans 
									INNER JOIN
										member_groups 
										ON id = ms_groupid');
		
		$output = '<legend>Membership Plans <button id="membership_plan_add" class="btn btn-info"><i class="icon-plus-sign icon-white"></i> Add Plan</button></legend>
						
						<p>When making changes (ie. deleting or saving plans) it might take a while depending on the number of plans you have on your list, as the system will be contacting the PayPal servers to generate the secure Pay Now buttons. However they will then be cached so the users will no experience any delay in page loading speed.</p>
						<table class="table memberships_list">
							<thead>
								<tr>
									<th>
										Plan Name
									</th>
									<th>
										Plan Description/features
										<a href="#" data-rel="tooltip" data-title="Features are separated with linebreaks. 1 feature per line."><i class="icon-question-sign"></i></a></th>
									<th>
										Plan Duration
										<a href="#" data-rel="tooltip" data-title="The duration of the plan. Members will be changed to the default member group once the plan runs out."><i class="icon-question-sign"></i></a>
									</th>
									<th>
										Price
										<a href="#" data-rel="tooltip" data-title="How much this plan costs. Please use the following format: xx.xx (eg: 12.99)"><i class="icon-question-sign"></i></a>
									</th>
									<th>
										Plan Group
										<a href="#" data-rel="tooltip" data-title="Members will be moved to this member group when they purchase this plan."><i class="icon-question-sign"></i></a>
									</th>
									<th>
										<a href="#" data-rel="tooltip" data-title="If you delete a plan, all members currently in this plan will be moved to the default usergroup."><i class="icon-question-sign"></i></a>
									</th>
								</tr>
							</thead>
							<tbody>';
		
		$durtypes = $this->getList('duration');
		
		foreach($plans as $plan){
			$dur = $durtypes[$plan['ms_durationtype']];
			
			$output .= '<tr id="membership_row_'.$plan['ms_id'].'">
							<td>
								<a href="#" data-rel="tooltip" data-title="Number of members subcribed to this plan: '.$plan['total'].'"><i class="icon-list"></i></a>
								<a href="#" class="membership_'.$plan['ms_id'].'_editable plandata" data-html="true" data-type="text" data-name="membership_plan_name['.$plan['ms_id'].']" data-original-title="Enter plan name" data-value="'.$plan['ms_name'].'"></a>
							<td>
								<a href="#" class="membership_'.$plan['ms_id'].'_editable plandata" data-html="true" data-type="textarea" data-name="membership_plan_description['.$plan['ms_id'].']" data-original-title="Enter Plan Description" data-value="'.$plan['ms_description'].'"></a>
							</td>
							<td>
								<a href="#" class="membership_'.$plan['ms_id'].'_editable plandata" data-html="true" data-type="text" data-inputclass="input-mini" data-name="membership_plan_duration['.$plan['ms_id'].']" data-original-title="Enter Plan Duration" data-value="'.$plan['ms_duration'].'"></a>&nbsp;
								
								<a href="#" class="membership_'.$plan['ms_id'].'_editable plandata" data-inputclass="input-small" data-html="true" data-type="select" data-name="membership_plan_durationtype['.$plan['ms_id'].']" data-original-title="Select Duration Type" data-source="ajax_calls.php?call=getlist&list=duration" data-value="'.$plan['ms_durationtype'].'">'.$dur.'</a>
							</td>
							<td>
								<a href="#" class="membership_'.$plan['ms_id'].'_editable plandata" data-html="true" data-type="text" data-inputclass="input-mini" data-name="membership_plan_price['.$plan['ms_id'].']" data-original-title="Enter Price" data-value="'.$plan['ms_price'].'"></a>
							</td>
							<td>
								<a href="#" class="membership_'.$plan['ms_id'].'_editable plandata" data-html="true" data-type="select" data-name="membership_plan_group['.$plan['ms_id'].']" data-original-title="Select Member Group" data-source="ajax_calls.php?call=getlist&list=groups" data-value="'.$plan['ms_groupid'].'">'.$plan['name'].'</a>
							</td>
							<td><button class="btn btn-warning membership_plan_delete">Delete Plan</button></td>
						</tr>';
		}
		
		$output .='</tbody>
				</table>';
		
		return $output;
	}
	
	public function deleteMembership(){
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(empty($_POST['msid'])){
			return false;
		}
		
		$getdefault = $this->sql->query('SELECT id FROM member_groups WHERE default_group = 1');
		$default = $getdefault->fetchAll(PDO::FETCH_ASSOC);
		$getdefault->closeCursor();
		
		if(empty($default[0]['id'])){
			return false;
		}
		
		$_POST['msid'] = $this->sanitize($_POST['msid'],'integer');
		$stmt = $this->sql->prepare('UPDATE members SET subscription_id = "", membergroup = ? WHERE subscription_id = ? AND firstadmin = 0');
		$stmt->execute(array($default[0]['id'], $_POST['msid']));
		$stmt->closeCursor();
		
		$stmt = $this->sql->prepare('DELETE FROM membership_plans WHERE ms_id = ?');
		$stmt->execute(array($_POST['msid']));
		$stmt->closeCursor();
		
		// Re-cache the buttons.
		$this->generateButtons();
		
		return array('status'=>true, 'message'=>'Membership Plan Deleted. Members where moved to the default usergroup. - Plans re-cached');
	}
	
	public function saveMembershipPlans(){
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return array('status'=>false, 'message'=>'Not permitted.');
		}
		
		if(empty($_POST['membership_plan_name'])){
			return array('status'=>false, 'message'=>'The list is empty, no plans where saved.');
		}
		
		$data = array();
		$query = '';
		foreach($_POST['membership_plan_name'] as $id => $group){
			if(!empty($id)){
				if(empty($_POST['membership_plan_name'][$id]) ||
				   empty($_POST['membership_plan_price'][$id]) ||
				   empty($_POST['membership_plan_duration'][$id]) ||
				   empty($_POST['membership_plan_durationtype'][$id]) ||
				   empty($_POST['membership_plan_group'][$id])
				){
					return array('status'=>false, 'message'=>'Please fill out all the required fields.');
				}
				
				$query .= (!empty($query) ? ',' : '').'(?,?,?,?,?,?,?)';
				$data[] = $this->sanitize($id, 'integer');
				$data[] = $this->sanitize($_POST['membership_plan_name'][$id], 'string');
				$data[] = $this->sanitize($_POST['membership_plan_description'][$id], 'string');
				$data[] = $this->sanitize($_POST['membership_plan_price'][$id], 'float');
				$data[] = $this->sanitize($_POST['membership_plan_duration'][$id], 'integer');
				$data[] = $this->sanitize($_POST['membership_plan_durationtype'][$id], 'integer');
				$data[] = $this->sanitize($_POST['membership_plan_group'][$id], 'integer');
			}
		}
		
		$stmt = $this->sql->prepare('INSERT INTO 
												membership_plans 
										VALUES 
											'.$query.'
									ON DUPLICATE KEY UPDATE 
										ms_name = VALUES(ms_name),
										ms_description = VALUES(ms_description),
										ms_price = VALUES(ms_price),
										ms_duration = VALUES(ms_duration),
										ms_durationtype = VALUES(ms_durationtype),
										ms_groupid = VALUES(ms_groupid)');
		$stmt->execute($data);
		
		// Re-cache the buttons.
		$generate = $this->generateButtons();
		if(!empty($generate)){
			$caching = '<br /><strong>PayPal buttons updated successfully!</strong>';
			$status = true;
		}else{
			$caching = '<br /><strong>But there was a problem contacting PayPal! Buttons were NOT updated!</strong>';
			$status = false;
		};
		
		return array('status'=>$status, 'message'=>'Membership plans saved successfully'.$caching);
	}
	
	public function getList($list = ''){
		if(empty($list) && empty($_REQUEST['list'])){
			return array('status'=>false, 'msg'=>'Please select a list to retrive');
		}else{
			if(empty($list)){
				$list = $_REQUEST['list'];
			}
			
			switch($list){
				case 'duration':
					return array(
								1 => 'Days',
								2 => 'Months',
								3 => 'Years'
							);
					break;
				case 'groups':
					$groups = array();
					foreach($this->sql->query('SELECT id, name FROM member_groups') as $grp){
						$groups[$grp['id']] = $grp['name'];
					}
					
					return $groups;
					break;
				
				default:
					return array('status'=>false, 'msg'=>'List not found.');
					break;
			}
		}
	}
	
	public function showSubscriptions(){
		if(!$this->config['paidmemberships']){
			return '';
		}
		
		$output = $this->cache_show('subscriptions', null, false);
		
		if(empty($output)){
			$output = $this->generateButtons();
		}
		
		return $output;
	}
	
	public function showPurchases($uid = ''){
		if(!$this->config['paidmemberships']){
			return '';
		}
		
		$stmt = $this->sql->prepare('SELECT * FROM member_payments WHERE user_id = ? ORDER BY id DESC');
		$stmt->execute(array((empty($uid) ? $this->sanitize($this->uid, 'integer') : $this->sanitize($uid, 'integer'))));
		
		$output = '<legend>Payments Log</legend>
					<p>Here you will be able to see any registered payments made since the account was created.</p>
					<table class="table table-striped">
						<thead>
							<tr>
								<th>Transaction #</th>
								<th>Subscription</th>
								<th>Date</th>
								<th>Expiry</th>
								<th>Price</th>
								<th>Status</th>
							</tr>
						</thead>
						<tbody>';
		
		if($stmt->rowCount() > 0){		
			foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $plan){
				$status_txt = $plan['status'];
				
				switch($plan['status']){
					case 'Completed':
						$status = 'success';
						break;
						
					case 'Pending':
						$status = 'warning';
						break;
						
					case 'Denied':
					case 'Expired':
					case 'Failed':
					case 'Voided':
						$status = 'error';
						break;
						
					default:
						$status = 'info';
						break;
				}
				
				$expiry = ' - ';
				if($status == 'success'){
					$markup = 'plan_no_'.$plan['plan_id'];
					$expiry = $this->processtime($plan['expiry_date'], false, true);
				}
				
				$output .= '<tr class="'.$status.'">
								<td>'.$plan['trans_id'].'</td>
								<td>'.$plan['plan_name'].'</td>
								<td>'.date('H:i - d-m-Y', $plan['payment_date']).'</td>
								<td>'.$expiry.'</td>
								<td>'.$plan['amount'].'</td>
								<td>'.$plan['status'].'</td>
							</tr>';
			}
		}
		
		$stmt->closeCursor();
			$output .= '</tbody>
					</table>';
		
		if(!empty($markup)){
			$output .= '<script>$(document).ready(function(){ highlightsub("'.$markup.'"); });</script>';
		}
		
		return $output;
	}
	
	protected function generateButtons($erroroutput = true){
		$output = '<div id="pricing">';
		
		foreach($this->sql->query('SELECT * FROM membership_plans ORDER BY ms_price ASC') as $row){
		
			// Generate a secure payment button from PayPal
			$sendPayData = array(
							'METHOD' => 'BMCreateButton',
							'VERSION' => '94.0',
							'USER' => $this->config['pp_api_user'],
							'PWD' => $this->config['pp_api_pass'],
							'SIGNATURE' => $this->config['pp_api_sig'],
							'BUTTONCODE' => 'ENCRYPTED',
							'BUTTONTYPE' => 'BUYNOW',
							'BUTTONSUBTYPE' => 'SERVICES',
							'L_BUTTONVAR1' => 'custom='.serialize(array('uid'=>$this->uid, 'plan_id'=>$row['ms_id'])),
							'L_BUTTONVAR5' => 'item_name='.$row['ms_name'],
							'L_BUTTONVAR7' => 'amount='.$row['ms_price'],
							'L_BUTTONVAR8' => 'currency_code='.$this->config['ipn_currency'],
							'L_BUTTONVAR9' => 'no_shipping=1',
							'L_BUTTONVAR10' => 'no_note=1',
							'L_BUTTONVAR11' => 'notify_url='.$this->base_url.'/system/ipn.php', //this is the IPN callback URL
							'L_BUTTONVAR12' => 'cancel_return='.$this->base_url.'/settings.php#/memberships',
							'L_BUTTONVAR13' => 'return='.$this->base_url.'/settings.php#/memberships?payment=success'
						);
			
			if($this->config['ipn_sandbox']){
				$posturl = 'sandbox.paypal.com';
			}else{
				$posturl = 'paypal.com';
			}
	
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_URL, 'https://api-3t.'.$posturl.'/nvp?'.http_build_query($sendPayData));
			parse_str(curl_exec($curl), $button);
			curl_close($curl);
			
			if(empty($button['WEBSITECODE'])){
				if($erroroutput){
					$this->errorlog->logError(null, 'PayPal API details are incorrect. Uable to generate PayPal Buttons. PayPal Output: '.print_r($button));
				}
				return '';
				continue;
			}
		
			$output .= '<div id="plan_no_'.$row['ms_id'].'">
							<h2>'.$row['ms_name'].'</h2>
							<div class="dasher"></div>
							<ul>';
							
							foreach(explode(PHP_EOL, $row['ms_description']) as $desc){
								$output .= '<li><i class="icon-ok"></i> '.$desc.'</li>';
							}
				
				$duration = $this->getList('duration');
				
				$output .= '</ul>
							<div class="dasher"></div>
							<p><b>'.$this->currencies[$this->config['ipn_currency']]['symbol'].''.$row['ms_price'].'</b> <span>for '.$row['ms_duration'].' '.$duration[$row['ms_durationtype']].'</span></p>
							'.$button['WEBSITECODE'].'
						</div>';
		}
		
		$output .= '</div>';
		$this->cache('subscriptions', $output);
		
		return $output;
	}
	
	public function CheckSubscription(){
		if(!$this->config['paidmemberships']){
			return false;
		}

		if(!$this->loggedin && $this->config['crontype'] && (empty($_REQUEST['token']) || $_REQUEST['token'] != $this->config['crontoken'])){
			return false;
		}

		if(!$this->config['crontype'] && (!$this->loggedin || !empty($_SESSION['croncheck']) && $_SESSION['croncheck'] >= time() - $this->config['cronintval'])){
			return false;
			
		}else if(!$this->config['crontype']){
			$_SESSION['croncheck'] = time();
		}

		if($this->config['crontype']){
			$sql_where = 'p.expiry_date <= ?';
			$sql_data = array(time());
		}else{
			$sql_where = 'p.expiry_date <= ? AND p.user_id = ?';
			$sql_data = array(time(), $this->uid);
		}

		$stmt = $this->sql->prepare('SELECT 
											p.user_id,
											p.id as p_id,
											g.id as default,
											m.membergroup,
											m.other_membergroups
										FROM 
											member_payments as p 
										LEFT JOIN 
											member_groups as g 
											ON default_group = 2
										INNER JOIN
											members as m
											ON m.id = p.user_id
										WHERE 
											'.$sql_where);
		$stmt->execute($sql_data);
		$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();

		if($stmt->rowCount() > 0){
			$payment_ids = array();
			$stmt = $this->sql->prepare('UPDATE members SET membergroup = ?, other_membergroups = ? WHERE id = ?');
			
			foreach($users as $usr){
				if(empty($usr['default'])){
					if($this->config['crondebug']){
						$this->errorlog->logError(null, 'There are no default user group! Unable to downgrade user subscriptions.');
					}
					exit;
				}
				$sqldata = array();
				
				$default = $usr['default'];
				$payment_ids[] = $usr['p_id'];
				
				//main group
				$sqldata[] = $usr['default'];
			
				//other groups
				$groups = explode(',', $usr['other_membergroups']);
				$groups[] = $usr['membergroup'];
				$grps = '';
				foreach($groups as $grp){
					if($grp != $_POST['primary_group'] && $grp != $usr['default']){
						$grps .= (empty($grps) ? '' : ',').$grp;
					}
				}
				$sqldata[] = $grps;
				$sqldata[] = $usr['user_id'];
				
				$stmt->execute($sqldata);
			}
			$stmt->closeCursor();
			
			$stmt = $this->sql->exec('UPDATE member_payment SET status = \'Expired\' WHERE id IN('.implode(',', $payment_ids).')');
			
			if($this->config['crondebug']){
				if($this->config['crontype']){
					$this->errorlog->logError(null, 'Cronjob ran successfully. '.count($payment_ids).' Payments expired.');
				}else{
					$this->errorlog->logError(null, 'Cronjob ran successfully. '.$this->format_ending($this->username).' payment expired.');
				}
			}
		}else{
			if($this->config['crondebug'] && $this->config['crontype']){
				$this->errorlog->logError(null, 'No payment plans where expired this time.');
			}
		}
	}
}