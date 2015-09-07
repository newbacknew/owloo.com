<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.4.0 																		 *
 *****************************************************************************************/
 
if(!defined('IN_SYSTEM')){
	exit;
}
class UserUploads extends Search{
	public function process_uploadavatar($uploadavatar = null, $admin_edit = false){
		if(!$this->loggedin){
			exit;
		}
		$userdata = array('id'=>$_SESSION['uid'], 'username'=>$_SESSION['udata']['avatar'], 'avatar'=> $_SESSION['udata']['avatar']);
		
		if($admin_edit && (empty($this->permissions['admin']) || !$this->permissions['admin'])){
			exit;
		}else if($admin_edit && $this->permissions['admin']){
			$stmt = $this->sql->prepare("SELECT id, username, avatar FROM members WHERE id = ?");
			$stmt->execute(array($_REQUEST['uid']));
			if($stmt->rowCount() < 1){
				return false;
			}
			$userdata = $stmt->fetchAll();
			$userdata = $userdata[0];
		}
		if(!empty($_FILES['avatar']) || !empty($uploadavatar)){
			// gallery settings. 
			$config = array();
			$config['bigImageX']		= 180;
			$config['bigImageY']		= 180;
			$config['midImageX']		= 110;
			$config['midImageY']		= 110;
			$config['smallImageX']		= 32;
			$config['smallImageY']		= 32;
			$config['imageDir']			= $this->config['avatar_dir'];
			$config['max_upload_size']	= 200;
			function imageResize($parameters){
				$fileTmp	= $parameters['sourceFile'];	//$_FILES['Filedata']['tmp_name'];
				$imageInfo	= $parameters['imageInfo'];
				$outputFile	= $parameters['destinationFile'];
				$newWidth	= $resizeWidth	= $parameters['width'];	//120;
				$newHeight	= $resizeHeight	= $parameters['height'];	//90;
				$origRatio	= $imageInfo[0]/$imageInfo[1];
				$offsetX	= $offsetY = 0;
				$resizeRatio= $resizeWidth/$resizeHeight;
				if($imageInfo[0]<$resizeWidth && $imageInfo[1]<$resizeHeight){
					@copy($fileTmp,$outputFile);
					return;
				}
				switch($imageInfo[2]){
					case 1:
						$origImg = imagecreatefromgif($fileTmp);
						break;
						
					case 2:
						$origImg = imagecreatefromjpeg($fileTmp);
						break;
						
					default:
						$origImg = imagecreatefrompng($fileTmp);
						break;
				}
				
				$method = 'crop'; //scale(no se corta la imagen) o crop
				
				if($method == 'crop' && ($imageInfo[0]<$resizeWidth || $imageInfo[1]<$resizeHeight)) $method = 'scale';
				if($method == 'scale'){
					if($origRatio > $resizeRatio){
						$quot = $resizeWidth / $imageInfo[0];
						$resizeHeight = $newHeight = round($imageInfo[1]*$quot);
						$newWidth = $resizeWidth;
					}else if( $origRatio < $resizeRatio){
						$quot = $resizeHeight / $imageInfo[1];
						$resizeWidth = $newWidth = round($imageInfo[0]*$quot);
						$newHeight = $resizeHeight;
					}
				}else if($method=='crop'){
					if($origRatio > $resizeRatio){
						$quot = $resizeHeight / $imageInfo[1];
						$newHeight = $resizeHeight;
						$newWidth = round($imageInfo[0]*$quot);
						$offsetX = round( (($newWidth-$resizeWidth)/2)/$quot );
					}else if( $origRatio < $resizeRatio){
						$quot = $resizeWidth / $imageInfo[0];
						$newWidth = $resizeWidth;
						$newHeight = round($imageInfo[1]*$quot);
						$offsetY = round( (($newHeight-$resizeHeight)/2)/$quot );
					}
				}
				
				$newImg = imagecreatetruecolor($resizeWidth,$resizeHeight);
				imagealphablending($newImg, false );
				imagesavealpha($newImg, true );
				imagecopyresampled($newImg, $origImg, 0, 0,$offsetX,$offsetY, $newWidth, $newHeight, $imageInfo[0], $imageInfo[1]); 
				switch($imageInfo[2]){
					case 1:
						imagegif($newImg, $outputFile);
						break;
						
					case 2:
						imagejpeg($newImg, $outputFile,100);
						break;
						
					default:
						imagepng($newImg, $outputFile);
						break;
				}
				imagedestroy($newImg);
				imagedestroy($origImg);
			}
			$continue_upload = false;
			$filename = sha1($userdata['username'].time());
			if(!empty($_FILES['avatar']['tmp_name'])){
				$array_last 	 = explode(".",$_FILES['avatar']['name']);
				$c 				 = count($array_last)-1;
				$ext 			 = strtolower($array_last[$c]);
				$fileupload_name = $filename;
				$file 			 = $_FILES['avatar']['tmp_name'];
				$temp_file		 = false;
				
				$allowed_ext = array('jpg','JPG','jpeg','JPEG','png','gif');
				
				$continue_upload = true;
				
				if(!in_array($ext, $allowed_ext)){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_uploads_1'].' '.implode(', ',$allowed_ext);
					$continue_upload = false;
				}
				if($_FILES['avatar']['size'] >= 1048576){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_uploads_2'].' '.(1048576/1024).' Kb.';
					$continue_upload = false;
				}
			}else{
				if(empty($_POST['new_avatar'])){
					
					$raw_image = file_get_contents('php://input');
					file_put_contents($config['imageDir'].'/temp/'.$filename.'.jpg', $raw_image);
					$fileupload_name = $filename;
					$file 			 = $config['imageDir'].'/temp/'.$filename.'.jpg';
					$temp_file		 = true;
					$continue_upload = true;
				}
			}
			if($continue_upload){
				if(!getimagesize($file)){
					$status['status'] = false;
					$status['message'] = $this->lang['cl_uploads_3'];
					
					if(empty($_FILES['avatar'])){
						echo json_encode($status);
						exit;
					}
				}else{
					//delete the old avatar
					if(!empty($userdata['avatar'])){
						@unlink($config['imageDir'].'/b/'.$userdata['avatar']);
						@unlink($config['imageDir'].'/s/'.$userdata['avatar']);
					}
					
					$imageInfo 	= getimagesize($file);
					
					imageResize(array(
						'sourceFile'		=> $file,
						'imageInfo'			=> $imageInfo,
						'destinationFile'	=> $config['imageDir'].'/b/'.$fileupload_name,
						'width'				=> $config['bigImageX'],
						'height'			=> $config['bigImageY']
					));
					imageResize(array(
						'sourceFile'		=> $file,
						'imageInfo'			=> $imageInfo,
						'destinationFile'	=> $config['imageDir'].'/s/'.$fileupload_name,
						'width'				=> $config['smallImageX'],
						'height'			=> $config['smallImageY']
					));
					if($temp_file){
						@unlink($file);
					}
					
					//change the chmod of the images
					@chmod($config['imageDir'].'/b/'.$fileupload_name, 0644);
					@chmod($config['imageDir'].'/s/'.$fileupload_name, 0644);
					
					$new_avtr = $this->sql->prepare("UPDATE members SET avatar=? WHERE id=?");
					$new_avtr->execute(array($fileupload_name, $userdata['id']));
					$new_avtr->closeCursor();
					$this->queries++;
					
					if(empty($_FILES['avatar'])){
						$this->avatar = $fileupload_name;
						$status['status'] = true;
						$status['img_name'] = $fileupload_name;
						$status['message'] = $this->lang['cl_uploads_4'];
						echo json_encode($status);
						exit;
					}else{
						if(!$admin_edit){
							$this->avatar = $fileupload_name;
							$_SESSION['udata']['avatar'] = $fileupload_name;
						}
					}
				}
			}
		}else{
			if(isset($status['status']) && !$status['status']){
			echo '<script type="text/javascript">
			$(document).ready(function(){
				showNotification("'.$status['message'].'", "Error", "error");
			});
			</script>';
		}
		}
	}
	
	function refresh_avatar(){
		if(!$this->loggedin){
			exit;
		}
		$stmt = $this->sql->prepare("SELECT avatar FROM members WHERE id=?");
		$stmt->execute(array($this->uid));
		$this->queries++;
		
		$data = $stmt->fetchAll();
		if($data[0]['avatar']){
			$status['status'] = true;
			$status['avatar'] = $data[0]['avatar'];
		}else{
			$status['status'] = false;
		}
		
		return $status;
	}
}