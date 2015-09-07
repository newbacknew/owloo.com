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

class Template_forum{
	protected $sys = null;
	
	public function __construct($site){
		$this->sys = $site;
	}
	
	protected function postcount($count){
		switch(true){
			case($count <= 5): //3 hours
			default:
				$labeltype = '';
				break;
				
			case($count > 5 && $count <= 15): //24 hours
				$labeltype = 'badge-success';
				break;
				
			case($count > 15 && $count <= 35): //24 hours
				$labeltype = 'badge-info';
				break;
				
			case($count > 35 && $count <= 75): //24 hours
				$labeltype = 'badge-warning';
				break;
				
			case($now-$unix > 75): //7 days and more
				$labeltype = 'badge-important';
				break;
		}
		
		return '<span class="badge '.$labeltype.'">'.$count.'</span>';
	}
	
	public function forum_category($data, $topics, $posts, $hottopics, $subcats = ''){
		$new_topic = true;		
		$permissions = json_decode($data['c_permissions'], true);
		if(!empty($permissions['topicperm']) && !in_array($_SESSION['udata']['membergroup'], $permissions['topicperm'])){
			$new_topic = false;	
		}
		
		$output = '<div class="span6 forum_category">
						<div class="navbar">
							<div class="navbar-inner">
								<a class="brand" href="topics.php?cat='.$data['c_id'].'">'.$data['c_name'].'</a>								
								<div class="pull-right"">
									'.($new_topic ? '<a href="newtopic.php?cat='.$data['c_id'].'" class="btn btn-small btn-success">'.$this->sys->lang['cl_admin_253'].'</a>' : '').'
									<a href="topics.php?cat='.$data['c_id'].'" class="btn btn-small btn-info">'.$this->sys->lang['cl_admin_254'].'</a>
								</div>
							</div>
						</div>
						<div class="fc_body">
						
							<!-- Forum Category Tabs -->
							<div class="tabs-left">
								<ul class="nav nav-tabs">
									<li class="active"><a href="#newtopics_'.$data['c_id'].'" data-toggle="tab"><i class="icon-comment"></i> '.$this->sys->lang['cl_admin_255'].'</a></li>
									<li><a href="#newposts_'.$data['c_id'].'" data-toggle="tab"><i class="icon-share-alt"></i> '.$this->sys->lang['cl_admin_256'].'</a></li>
									<li><a href="#hottopics_'.$data['c_id'].'" data-toggle="tab"><i class="icon-fire"></i> '.$this->sys->lang['cl_admin_257'].'</a></li>
			 '.(!empty($subcats) ? '<li><a href="#subcats_'.$data['c_id'].'" data-toggle="tab"><i class="icon-folder-open"></i> '.$this->sys->lang['cl_admin_258'].'</a></li>' : '').'
								</ul>

								<div class="tab-content">
								
									<!-- Latest Topics -->
									<div class="tab-pane active" id="newtopics_'.$data['c_id'].'">';
									
									foreach($topics as $key => $tpc){
										if($tpc['p_catid'] == $data['c_id']){
											$output .= '<div class="tab_item">
															<i class="icon-bullhorn"></i> <a href="posts.php?topic='.$tpc['p_id'].'">'.$tpc['p_topictitle'].'</a><br />
															- Topic by 
															
															<a href="profile.php?user='.$tpc['username'].'" class="usr-tooltip label" style="background-color: #'.$tpc['colour'].';" data-avatar="'.$tpc['avatar'].'">'.$tpc['username'].'</a>, posted '.$this->sys->processtime($tpc['p_postdate'], true).'
														</div>';
														
											$output .= '<div class="forum_spacer"></div>';
											
											unset($topics[$key]);
										}
									}
									
									
						$output .= '</div>
									
									<!-- Latest Posts -->
									<div class="tab-pane" id="newposts_'.$data['c_id'].'">';
									
									foreach($posts as $key => $pst){
										if($pst['p_catid'] == $data['c_id']){
											$output .= '<div class="tab_item">
															<i class="icon-bullhorn"></i> Topic: <a href="posts.php?topic='.$pst['p_topicid'].'">'.$pst['title'].'</a><br />
															<img class="nav_avatar" src="http://zolidweb.com/projects/gj02jg0whj845/images/default_small.png" alt="" /> 
															<a href="profile.php?user='.$tpc['username'].'" class="usr-tooltip label" style="background-color: #'.$pst['colour'].';" data-avatar="'.$pst['avatar'].'">'.$pst['username'].'</a> replied '.$this->sys->processtime($pst['p_postdate'], true).'
														</div>';
														
											$output .= '<div class="forum_spacer"></div>';
											
											unset($posts[$key]);
										}
									}
									
									
						$output .= '</div>
									
									<!-- Host Topics -->
									<div class="tab-pane" id="hottopics_'.$data['c_id'].'">';
									
									foreach($hottopics as $key => $hot){
										if($hot['p_catid'] == $data['c_id']){
											$output .= '<div class="tab_item">
															<i class="icon-bullhorn"></i> <a href="posts.php?topic='.$hot['p_topicid'].'">'.$hot['title'].'</a><br />
															'.$this->sys->lang['cl_admin_259'].' '.$this->postcount($hot['total']).' '.$this->sys->lang['cl_admin_260'].' '.$this->sys->processtime($hot['p_postdate'], true).'
														</div>';
														
											$output .= '<div class="forum_spacer"></div>';
											
											unset($hottopics[$key]);
										}
									}
									
									
						$output .= '</div>';
							
					if(!empty($subcats)){
						$output .= '<!-- Sub categories -->
									<div class="tab-pane" id="subcats_'.$data['c_id'].'">';
									
									foreach($subcats as $subc){
										$output .= '<div class="tab_item">
														<i class="icon-folder-open"></i> <a href="topics.php?cat='.$subc['c_id'].'">'.$subc['c_name'].'</a>
													</div>';
													
										$output .= '<div class="forum_spacer"></div>';
									}
						
						$output .= '</div>';
					}
									
					
					$output .= '</div>
							</div>
							
							
							
							<div class="clearfix"></div>
						</div>
					</div>';
		return $output;
	}
	
	public function forum_subcategory($data){
		$output = '<div class="span6 forum_category">
						<div class="navbar">
							<div class="navbar-inner">
								<a class="brand" href="topics.php?cat='.$data['c_id'].'">'.$data['c_name'].'</a>								
								<div class="pull-right"">
									<a href="newtopic.php?cat='.$data['c_id'].'" class="btn btn-small btn-success">'.$this->sys->lang['cl_admin_261'].'</a>
									<a href="topics.php?cat='.$data['c_id'].'" class="btn btn-small btn-info">'.$this->sys->lang['cl_admin_262'].'</a>
								</div>
							</div>
						</div>
					</div>';
		return $output;
	}
	
	public function forum_topic($topic){
		return '<div class="span6 forum_category">
					'.($topic['p_sticky'] ? '<div class="stickyTopic"></div>' : '').'
					'.($topic['p_locked'] ? '<div class="lockedTopic"></div>' : '').'
					<a href="posts.php?topic='.$topic['p_topicid'].'" class="fc_topic">
						<i class="icon-bullhorn"></i> <span class="f_title"><strong>'.$topic['p_topictitle'].'</strong></span>
						<div class="forum_spacer"></div>
						'.$this->sys->lang['cl_admin_263'].' <span class="usr-tooltip label" style="background-color: #'.$topic['colour'].';" data-avatar="'.$topic['avatar'].'">'.$topic['username'].'</span> '.$this->sys->processtime($topic['p_postdate'], true).'
						<div class="clearfix"></div>
						<div class="forum_spacer"></div>
						<div class="pull-left">'.$this->postcount($topic['total']).' '.$this->sys->lang['cl_admin_264'].'</div>
						<div class="pull-right">'.(!empty($topic['p_replydate']) ? $this->sys->lang['cl_admin_265'].' '.$this->sys->processtime($topic['p_replydate'], true) : '').'</div>
						<div class="clearfix"></div>
					</a>
				</div>';
	}
	
	public function forum_post($topic, $token){
		$button = '';
		if(!empty($this->sys->permissions['admin']) && $this->sys->permissions['admin']){
			if($topic['p_topicid'] != 0){
				$button .= ' - <a href="editpost.php?post='.$this->sys->sanitize($topic['p_id'], 'integer').'" class="btn btn-info btn-mini">'.$this->sys->lang['cl_admin_272'].'</a>
							<button data-tokenname="edittopic" data-token="'.$token.'" data-tid="'.$this->sys->sanitize($topic['p_id'], 'integer').'" class="deletePost btn btn-danger btn-mini">'.$this->sys->lang['cl_admin_271'].'</button>';
			}
		}
		
		return '<div id="post_'.$topic['p_id'].'">
					<div class="span2 forum_post">
						<a href="#"><img class="post_avatar" src="'.(!empty($topic['avatar']) ? 'uploads/avatars/b/'.$topic['avatar'] : 'images/default_big.png').'" alt=""></a>
						<ul class="post_legacy">
							<li><a href="profile.php?user='.$topic['username'].'">'.$topic['username'].'</a></li>
							<li>Posts: '.$topic['f_posts'].'</li>
						</ul>
						<button class="btn btn-mini btn-info btn-block" onclick="profile_newpm(\''.$topic['username'].'\');">'.$this->sys->lang['cl_admin_266'].'</button>
					</div>
					<div class="span10 forum_post">
						<div class="post_body">
							<span>'.$this->sys->lang['cl_admin_267'].' '.date($this->sys->config('dateformat_long'), $topic['p_postdate']).''.$button.'</span>
							<div class="forum_spacer"></div>
							'.$topic['p_post'].'
						</div>
					</div>
					<div class="clearfix "></div>
				</div>
				<div class="forum_spacer"></div>';
	}
}