<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.4.0 																		 *
 *****************************************************************************************/
if(empty($_POST)){
	exit();
}

define('IPN', true);
require_once('initiater.php');

if($site->config('ipn_debug')){
	$site->errorlog->logError(null, print_r($_POST, true));
}

try {
    $verified = $site->processIpn();
} catch (Exception $e) {
    // fatal error trying to process IPN.
	if($site->config('ipn_debug')){
		$site->errorlog->logError(null, print_r($e, true));
	}
    exit();
}

	
if($verified){
	// Get the details for the membership plan the user has purchased, sanitize the required input.
	$_POST['custom'] = unserialize($_POST['custom']);
	$_POST['custom']['uid'] = $site->sanitize($_POST['custom']['uid'], 'integer');
	$_POST['custom']['plan_id'] = $site->sanitize($_POST['custom']['plan_id'], 'integer');
	
	$stmt = $site->sql->prepare('SELECT * FROM member_payments WHERE user_id = ? AND expiry_date > ?');
	$stmt->execute(array($site->sanitize($_POST['custom']['uid'], 'integer'), time()+120));
	if($stmt->rowCount() > 0){
		$current_plan = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$current_plan = $current_plan[0];
	}else{
		if($site->config('ipn_debug')){
			$site->errorlog->logError(null, 'No current plans: '.print_r(array($site->sanitize($site->uid, 'integer'), time()+120), true));
		}
	}
	$stmt->closeCursor();
	
	$stmt = $site->sql->prepare('SELECT * FROM membership_plans WHERE ms_id = ?');
	$stmt->execute(array($_POST['custom']['plan_id']));
	
	// Check if the plan was found
	if($stmt->rowCount() < 1){
		// else make the values secure for output, and log them to the error file.
		foreach($_POST as $key => $val){
			$_POST[$key] = $site->sanitize($val, 'string');
		}
		if($site->config('ipn_debug')){
			$err = $stmt->errorInfo();
			$site->errorlog->logError(null, 'Invalid IPN notification. SQL Error: '.print_r($err[2], true).', Dumping details: '.print_r($_POST, true));
		}
		exit();
	}
	
	// If the plan was found, fetch the details from the database and sanitize the $_POST values, while checking if the values match.
	$plan = $stmt->fetchAll(PDO::FETCH_ASSOC);
	$plan = $plan[0];
	$stmt->closeCursor();
	
	// Additional check to see if the price, currency and business is correct.
	$price_match = false;
	$currency_match = false;
	$seller_match = false;
	foreach($_POST as $key => $val){
		if($key != 'custom'){
			$_POST[$key] = $site->sanitize($val, 'string');
		
			switch($key){
				case 'mc_gross':
					if($val == $plan['ms_price']){
						$price_match = true;
					}
					break;
				case 'mc_currency':
					if($val == $site->config('ipn_currency')){
						$currency_match = true;
					}
				case 'receiver_email':
					if($val == $site->config('ipn_email')){
						$seller_match = true;
					}
					break;
			}
		}
	}
	
	if(!$price_match || !$currency_match || !$seller_match){
		if($site->config('ipn_debug')){
			$site->errorlog->logError(null, 'Invalid Payment, information does not match (Price='.($price_match ? 'true' : 'false').', Currency='.($currency_match ? 'true' : 'false').', Receiver='.($seller_match ? 'true' : 'false').') . Dumping details: '.print_r($_POST, true));
		}
		exit();
	}
	
	$paymentComplete = ($_POST['payment_status'] == 'Completed' ? true : false);
	
	$duration = 0;
	switch($plan['ms_durationtype']){
		case 1:
			$duration = 60*60*24;
			break;
		case 2:
			$duration = 60*60*24*30;
			break;
		case 3:
			$duration = 60*60*24*365;
			break;
	}
	
	// Expand the new plan with what is left of the old one (Upgrade/Downgrading/Renew)
	if($site->config('ipn_debug')){
		$site->errorlog->logError(null, 'Protating ?');
	}
	if(!empty($current_plan) && $paymentComplete){
		if($site->config('ipn_debug')){
			$site->errorlog->logError(null, 'Yes, prorate the plan');
		}
		// Find the remaining value of the current plan
		$valueleft = ($current_plan['amount'] / $current_plan['plan_length']) * ($current_plan['expiry_date'] - time());
		
		// Find out how much is a second cost of the new plan
		$onesecond = $plan['ms_price'] / ($plan['ms_duration'] * $duration);
		
		// how many seconds does that buy us with the current plan's remaining value?
		$extendtime = round($valueleft / $onesecond);
		if($site->config('ipn_debug')){
			$site->errorlog->logError(null, 'Poration: valueleft: '.$valueleft.', onesecond: '.$onesecond.', extendtime: '.$extendtime);
		}
	}else{
		if($site->config('ipn_debug')){
			$site->errorlog->logError(null, 'No, do not prorate the plan. '.print_r($paymentComplete,true).''.(!empty($current_plan) ? print_r($current_plan,true) : ''));
		}
		$extendtime = 0;
	}
	
	$expiry = (time() + $plan['ms_duration']*$duration) + $extendtime;
	
	//verified payment
	$data = array(
				$_POST['custom']['uid'],
				$_POST['custom']['plan_id'],
				$plan['ms_groupid'],
				$plan['ms_name'],
				time(),
				$expiry,
				($plan['ms_duration']*$duration),
				$_POST['mc_gross'],
				serialize(array(
							'gross' => $_POST['mc_gross'],
							'fee' => (empty($_POST['mc_fee']) ? 0 : $_POST['mc_fee']),
							'currency' => $_POST['mc_currency'],
							'item_name' => $_POST['item_name'],
							'test_ipn' => $_POST['test_ipn'],
							'ipn_track_id' => ($_POST['test_ipn'] ? 'test_'.$_POST['txn_id'] : $_POST['ipn_track_id'])
						)),
				$_POST['payment_status'],
				$_POST['txn_id']
			);
			
	$stmt = $site->sql->prepare('INSERT INTO 
									member_payments 
									(user_id, plan_id, g_id, plan_name, payment_date, expiry_date, plan_length, amount, returndata, status, trans_id)
								VALUES
									(?,?,?,?,?,?,?,?,?,?,?)
							ON DUPLICATE KEY UPDATE 
									`user_id` = VALUES(user_id),
									`plan_id` = VALUES(plan_id),
									`plan_name` = VALUES(plan_name),
									`payment_date` = VALUES(payment_date),
									`expiry_date` = VALUES(expiry_date),
									`amount` = VALUES(amount),
									`returndata` = VALUES(returndata),
									`status` = VALUES(status);');
	$stmt->execute($data);
	if($site->config('ipn_debug')){
		$site->errorlog->logError(null, 'Adding payment to database: '.print_r($data, true));
	}
	if($paymentComplete){
		$stmt = $site->sql->prepare('SELECT 
											m.membergroup,
											m.other_membergroups,
											g.name,
											g.permissions
										FROM 
											members as m
										INNER JOIN
											member_groups as g
											ON	g.id = m.membergroup
										WHERE 
											m.id = ?');
		$stmt->execute(array($_POST['custom']['uid']));
		if($stmt->rowCount() < 0){
			if($site->config('ipn_debug')){
				$site->errorlog->logError(null, 'Unable to update user. User not found! '.print_r(awdawd, true));
			}
			exit;
		}
		$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$user = $user[0];
		$user['permissions'] = unserialize($user['permissions']);
		$stmt->closeCursor();
		
		/******************************
			Add the user to the group
		*******************************/
		// If it's and admin, we need add the new group to the additional groups and not main.
		if(!empty($user['permissions']['admin']) && $user['permissions']['admin']){
			$main_group = $user['membergroup'];
			
			if(!empty($user['other_membergroups'])){
				$additional_groups = explode(',', $user['other_membergroups']);
				
				if(!in_array($plan['ms_groupid'], $additional_groups)){
					$additional_groups[] = $plan['ms_groupid'];
					$additional_groups = implode(',',$additional_groups);
				}
			}else{
				$additional_groups = '';
			}
		}else{
			$main_group = $plan['ms_groupid'];
			
			if($user['membergroup'] != $plan['ms_groupid']){
				if(!empty($user['other_membergroups'])){
					$additional_groups = explode(',', $user['other_membergroups']);
					
					if(!empty($current_plan) && $user['membergroup'] != $current_plan['g_id']){
						foreach($additional_groups as $key => $val){
							if($val == $user['membergroup']){
								unset($additional_groups[$key]);
							}
						}
						if(count($additional_groups) < 1){
							$additional_groups = '';
						}
					}else{
						if(!in_array($user['membergroup'], $additional_groups)){
							$additional_groups[] = $user['membergroup'];
						}
					}
				}else{
					$additional_groups = $user['membergroup'];
				}
			}else{
				$additional_groups = '';
			}
		}
		
		if(is_array($additional_groups)){
			$additional_groups = implode(',',$additional_groups);
		}
		
		$p_id = $site->sql->lastInsertId();
		$stmt = $site->sql->prepare('UPDATE 
											members 
										SET 
											subscription_id = ?,
											subscription_start = ?,
											membergroup	= ?,
											other_membergroups = ?
										WHERE
											id = ?');
		$stmt->execute(array($p_id, time(), $main_group, $additional_groups, $_POST['custom']['uid']));
		$err = $stmt->errorInfo();
		
		if(!empty($err[2])){
			if($site->config('ipn_debug')){
				$site->errorlog->logError(null, 'Query error: '.print_r($err, true).' Dumping details: '.print_r(array($p_id, time(), $plan['ms_groupid'], $additional_groups, $_POST['custom']['uid']), true));
			}
		}
		
		if(!empty($current_plan)){
			$stmt = $site->sql->prepare('UPDATE 
											member_payments 
										SET 
											status = ?,
											expiry_date = ?
										WHERE
											id = ?');
			$stmt->execute(array('Prorated',0,$current_plan['id']));
			$stmt->closeCursor();
			$err = $stmt->errorInfo();
			
			if($site->config('ipn_debug')){
				$site->errorlog->logError(null, 'Protate Update payment: '.print_r(array('Prorated',0,$current_plan['id']), true));
			}
			
			if(!empty($err[2])){
				if($site->config('ipn_debug')){
					$site->errorlog->logError(null, 'Query error: '.print_r($err, true).' Dumping details: '.print_r(array('Prorated','-',$current_plan['id']), true));
				}
			}
		}
	}
} else {
	//invalid payment
	if($site->config('ipn_debug')){
		$site->errorlog->logError(null, 'InvalidPayment. Dumping details: '.print_r($_POST, true));
	}
	exit();
}