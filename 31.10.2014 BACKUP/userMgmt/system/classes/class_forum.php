<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon: http://codecanyon.net/item/solid-php-user-management-system-/1254295		 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.3.1 																		 *
 *****************************************************************************************/
 
if(!defined('IN_SYSTEM')){
	exit;
}

class Forum extends Memberships {
	private $_tmpl = null;
	private $BCsubs = array();
	private $breadcrumbs = array();
	
	public function __construct(){
		parent::__construct();
		
		if($this->config['forum_enabled'] || (!empty($this->permissions['admin']) && $this->permissions['admin'])){
			if(file_exists(SYSTEM_PATH.'/templates/class_template_forum.php')){
				require_once(SYSTEM_PATH.'/templates/class_template_forum.php');
				$this->_tmpl = new Template_forum($this);
			}else{
				die($this->lang['cl_admin_228']);
			}
		}
	}
	
	public function showCategories($navonly = false){
		if(!$this->config['forum_enabled'] && (empty($this->permissions['admin']) || !$this->permissions['admin'])){
			return false;
		}
		
		if(!$navonly){
			$get_no = 4;
			
			//Get top 4 latest topics
			$stmt = $this->sql->prepare('SELECT 
												* 
											FROM
												(
													SELECT 
														  *, 
														 @rank := CASE WHEN @ranked <> p_catid THEN 0 ELSE @rank+1 END AS rank,
														 @ranked := p_catid 
													 FROM 
														  (SELECT @rank := -1) a, 
														  (SELECT @ranked :=- -1) b, 
														  (SELECT forum_posts.*, members.id, members.avatar, members.username, g.colour
														  FROM forum_posts 
														  INNER JOIN
															  members
															  ON id = p_author
														  INNER JOIN
															  member_groups as g
															  ON g.id = membergroup
														   WHERE p_istopic = 1
														   ORDER BY p_catid, p_postdate DESC) c
												) ranked_posts
											WHERE 
												ranked_posts.rank <= '.($get_no-1));
			$stmt->execute();
			$this->queries++;
			$newtopics = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			
			//Get top 4 latest posts
			$stmt = $this->sql->prepare('SELECT 
												* 
											FROM
												(
													SELECT 
														  *, 
														 @rank := CASE WHEN @ranked <> p_catid THEN 0 ELSE @rank+1 END AS rank,
														 @ranked := p_catid 
													 FROM 
														  (SELECT @rank := -1) a, 
														  (SELECT @ranked :=- -1) b, 
														  (SELECT p1.*, members.id, members.avatar, members.username, g.colour, p2.p_topictitle as title
														  FROM forum_posts as p1
														  INNER JOIN
															  members
															  ON id = p_author
														  INNER JOIN
															  member_groups as g
															  ON g.id = membergroup
														  INNER JOIN
															  forum_posts as p2
															  ON p2.p_id = p1.p_topicid
														   WHERE p1.p_istopic = 0
														   ORDER BY p1.p_catid, p1.p_postdate DESC) c
												) ranked_posts
											WHERE 
												ranked_posts.rank <= '.($get_no-1));
			$stmt->execute();
			$this->queries++;
			$newposts = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			
			//Get top 4 hot topics
			$stmt = $this->sql->prepare('SELECT 
												* 
											FROM
												(
													SELECT 
														  *, 
														 @rank := CASE WHEN @ranked <> p_catid THEN 0 ELSE @rank+1 END AS rank,
														 @ranked := p_catid 
													 FROM 
														  (SELECT @rank := -1) a, 
														  (SELECT @ranked :=- -1) b, 
														  (SELECT
																forum_posts.*,
																COUNT(forum_posts.p_id) total, 
																members.id, 
																members.avatar, 
																members.username,
																p2.p_topictitle as title,
																g.colour
															FROM 
																forum_posts
															INNER JOIN
																members
																ON id = p_author
														    INNER JOIN
															    member_groups as g
															    ON g.id = membergroup
															INNER JOIN
															  forum_posts p2
															  ON p2.p_id = forum_posts.p_topicid OR p2.p_id = forum_posts.p_id
															GROUP BY
																p_topicid
															ORDER BY total DESC, p_postdate DESC) c
												) ranked_posts
											WHERE 
												ranked_posts.rank <= '.($get_no-1));
			$stmt->execute();
			$this->queries++;
			$hottopics = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
		}
		
		$categories = '';
		$c = 0;
		$this->queries++;
		$mains = array();
		$subs = array();
		foreach($this->sql->query('SELECT * FROM forum_categories ORDER BY c_sort') as $category){
			$permissions = json_decode($category['c_permissions'], true);
			if(!empty($permissions['viewperm']) && !in_array($_SESSION['udata']['membergroup'], $permissions['viewperm'])){
				continue;
			}
			
			if($category['c_sub'] == 0){
				$mains[] = $category;
			}else{
				$subs[$category['c_sub']][] = $category;
			}
		}
		
		foreach($mains as $category){
			if(!$navonly){
				
				if($c == 2){
					$c = 1;
					$categories .= '<div class="clearfix"></div>';
				}else{
					$c++;
				}
				
				$categories .= $this->_tmpl->forum_category($category, $newtopics, $newposts, $hottopics, (!empty($subs[$category['c_id']]) ? $subs[$category['c_id']] : ''));
			}
		}
		
		return $categories;
	}
	
	public function showTopics($navonly = false){
		if(!$this->config['forum_enabled'] && (empty($this->permissions['admin']) || !$this->permissions['admin'])){
			return false;
		}
		
		if(empty($_GET['cat'])){
			return $this->lang['cl_admin_229'];
		}
		
		$_GET['cat'] = $this->sanitize($_GET['cat'], 'integer');
		$_REQUEST['page'] = (!empty($_REQUEST['page']) ? $this->sanitize($_REQUEST['page'], 'integer') : 1);
		
		$results_per_page = 21;
		$_REQUEST['page'] = (!empty($_REQUEST['page']) ? $_REQUEST['page'] : 1);
		$offset = (empty($_REQUEST['page']) || $_REQUEST['page'] < 0 ? 0 : ($_REQUEST['page']*$results_per_page)-$results_per_page);
		
		$get_total = $this->sql->prepare('SELECT p_id FROM forum_posts WHERE p_catid = ? AND p_istopic = 1');
		$get_total->execute(array($_GET['cat']));
		$total = $get_total->rowCount();
		$get_total->closeCursor();
		
		$stmt = $this->sql->prepare('SELECT 
											c.*,
											p.*,
											u.username,
											u.id,
											u.avatar,
											g.colour,
											(SELECT COUNT(*) FROM forum_posts as p2 WHERE p2.p_topicid = p.p_id) as total
										FROM 
											forum_categories AS c
										LEFT JOIN
											forum_posts AS p
											ON p.p_catid = c.c_id 
												AND p.p_istopic = 1
										LEFT JOIN
											members AS u
											ON u.id = p.p_author
										LEFT JOIN
											member_groups AS g
											ON g.id = u.membergroup
										WHERE
											c.c_id = ?
										ORDER BY
											p.p_sticky DESC,
											p.p_replydate DESC,
											p.p_postdate DESC
										LIMIT '.$results_per_page.'
										OFFSET '.$offset);
		$stmt->execute(array($_GET['cat']));
		
		if($stmt->rowCount() > 0){
			$topics = '';
			$title = '';
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$permissions = json_decode($row['c_permissions'], true);
				
				if(!empty($permissions['viewperm']) && !in_array($_SESSION['udata']['membergroup'], $permissions['viewperm'])){
					continue;
				}
				
				$title = $row['c_name'];
				$this->breadcrumbs['category'] = $row['c_name'].'#'.$row['c_id'];
				
				if(!empty($row['p_topictitle'])){
					if($row['p_topicid'] == 0){
						$row['p_topicid'] = $row['p_id'];
					}
					$topics .= $this->_tmpl->forum_topic($row);
				}
			}
			$stmt->closeCursor();
			
			// Get sub categories;
			$stmt = $this->sql->prepare('SELECT * FROM forum_categories WHERE c_sub = ?');
			$stmt->execute(array($_GET['cat']));
			$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
			
			$subcats = '';
			if(!empty($categories)){
				foreach($categories as $subcat){
					$subcats .= $this->_tmpl->forum_subcategory($subcat);
				}
				$subcats .= '<div class="clearfix"></div>';
			}
			
			return $this->showCategories(true).$this->showForumBreadcrumbs($_GET['cat'], $title, $this->generatePagination($total, $_REQUEST['page'], null, $results_per_page), true, null, $permissions).$subcats.$topics;
		}
	}
	
	public function seofy($string){
		return  trim( strtolower( str_replace(' ', '-', preg_replace('/[^a-z0-9\s-]/', '', $string) ) ), '-');
	}
	
	public function process_tooltip(){
		if(empty($_REQUEST['user'])){
			return array('status' => false, 'message' => $this->lang['cl_admin_230']);
		}
		
		$_REQUEST['user'] = $this->sanitize($_REQUEST['user'], 'string');
		
		$stmt = $this->sql->prepare('SELECT 
											u.username,
											u.avatar,
											u.lastactivity,
											u.f_posts,
											g.name,
											g.colour
										FROM
											members as u
										INNER JOIN
											member_groups as g
											ON g.id = u.membergroup
										WHERE
											u.username = ?');
		$stmt->execute(array($_REQUEST['user']));
		
		if($stmt->rowCount() > 0){
			$user = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$user = $user[0];
			return array('status' => true, 'message' => '<div class="pull-left"><img src="'.(!empty($user['avatar']) ? $user['avatar'] : 'images/default_small.png').'" alt=""></div>
			<div class="pull-left usr-tt-info">
				<span class="label" style="background-color: #'.$user['colour'].';">'.$user['name'].'</span> - <strong>'.$user['username'].'</strong><br />
				'.$this->lang['cl_admin_231'].' '.(!empty($user['lastactivity']) ? date($this->config['dateformat_long'], $user['lastactivity']) : $this->lang['cl_admin_232']).'</div>
			<div class="clearfix"></div>
			<div class="forum_spacer"></div>
			<table>
				<tbody>
					<tr>
						<td></td>
						<td>'.$this->lang['cl_admin_233'].' '.$user['f_posts'].'</td>
					</tr>
				</tbody>
			</table>');
		}else{
			return array('status' => false, 'message' => $this->lang['cl_admin_234']);
		}
	}
	
	public function showPosts($showbc = true){
		if(!$this->config['forum_enabled'] && (empty($this->permissions['admin']) || !$this->permissions['admin'])){
			return false;
		}
		
		if(empty($_GET['topic']) && empty($_GET['post'])){
			if(empty($_GET['post'])){
				return $this->lang['cl_admin_235'];
			}else{
				$_GET['topic'] = $_GET['post'];
			}
		}
		
		$results_per_page = 10;
		$_REQUEST['page'] = (!empty($_REQUEST['page']) ? $this->sanitize(intval($_REQUEST['page']), 'integer') : 1);
		$offset = (empty($_REQUEST['page']) || $_REQUEST['page'] < 0 ? 0 : ($_REQUEST['page']*$results_per_page)-$results_per_page);
		
		$_GET['topic'] = $this->sanitize($_GET['topic'], 'integer');
		
		$get_total = $this->sql->prepare('SELECT p_id FROM forum_posts WHERE p_topicid = ? OR p_id = ?');
		$get_total->execute(array($_GET['topic'], $_GET['topic']));
		$total = $get_total->rowCount();
		$get_total->closeCursor();
		
		$stmt = $this->sql->prepare('SELECT 
											c.*,
											p.*,
											u.username,
											u.id,
											u.avatar,
											u.f_posts,
											g.colour,
											p2.p_topictitle as topic_title,
											p2.p_sticky as topic_sticky,
											p2.p_locked as topic_locked
										FROM 
											forum_posts AS p
										LEFT JOIN
											forum_categories AS c
											ON c.c_id = p.p_catid
										LEFT JOIN
											members AS u
											ON u.id = p.p_author
										LEFT JOIN
											member_groups AS g
											ON g.id = u.membergroup
										LEFT JOIN
											forum_posts as p2
											ON p2.p_id = ? 
										WHERE
											p.p_topicid = ?
										OR
											p.p_id = ?
										ORDER BY
											p.p_postdate ASC
										LIMIT '.$results_per_page.'
										OFFSET '.$offset);
										
		$stmt->execute(array($_GET['topic'],$_GET['topic'],$_GET['topic']));
		
		if($stmt->rowCount() > 0){
			$output = '';
			$title = '';
			$topic_cat = 0;
			$topic_details = array('locked'=>false, 'sticky'=>false);
			$token = $this->csrfGenerate('edittopic');
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$permissions = json_decode($row['c_permissions'], true);
				
				if(!empty($permissions['viewperm']) && !in_array($_SESSION['udata']['membergroup'], $permissions['viewperm'])){
					return '';
				}
				
				$this->breadcrumbs['category'] = $row['c_name'].'#'.$row['c_id'];
				$this->breadcrumbs['topic'] = $row['topic_title'].'#'.(empty($row['p_topicid']) ? $row['p_id'] : $row['p_topicid']);
				
				$row['p_post'] = $this->sanitize($row['p_post'], 'string');
				$output .= $this->_tmpl->forum_post($row, $token);
				$title = $row['topic_title'];
				$topic_cat = $row['p_catid'];
				
				$topic_details = array('cid'=>$row['p_catid'],'tid'=>$row['p_id'], 'locked'=>$row['topic_locked'], 'sticky'=>$row['topic_sticky']);
			}
			$stmt->closeCursor();
			return $this->showCategories(true).$this->showForumBreadcrumbs($topic_cat, $title, $this->generatePagination($total, $_REQUEST['page'], null, $results_per_page), $showbc, $topic_details, $permissions).$output;
		}else{
			$stmt->closeCursor();
			return $this->showCategories(true).$this->lang['cl_admin_236'];
		}
	}
	
	public function showForumBreadcrumbs($cid = null, $title = '', $pagination = '', $showbc = true, $topic_details = null, $permissions){
		$new_topic = true;
		$new_reply = true;
		if(!empty($permissions['topicperm']) && !in_array($_SESSION['udata']['membergroup'], $permissions['topicperm'])){
			$new_topic = false;	
		}
		
		if(!empty($permissions['replyperm']) && !in_array($_SESSION['udata']['membergroup'], $permissions['replyperm'])){
			$new_reply = false;	
		}
		
		$output = '<div class="span12">';
			if($showbc){
				if(!empty($cid)){
					$bc = $this->generateBC($cid);
					
					if(!empty($bc)){
						if(!empty($title) && $this->curpage == 'posts'){
							$bc[] = array('name' => $title);	
						}
						
						$c = count($bc);
						$i = 1;
						$output .= '<ul class="breadcrumb">
										<li><a href="forum.php">'.$this->lang['cl_admin_237'].'</a> <span class="divider">/</span></li>';
										
						foreach($bc as $bcc){
							if($i < $c){
								$output .= '<li><a href="topics.php?cat='.$bcc['id'].'">'.$bcc['name'].'</a> <span class="divider">/</span></li>';
							}else{
								$output .= '<li class="active">'.$bcc['name'].'</li>';
							}
							
							$i++;
						}
						
						$output .= '</ul>';
					}
				}
				
				if(!empty($title)){
					$button = '';
					if(empty($topic_details) || !$topic_details['locked']){
						$button = ($this->curpage == 'posts' ? 
									($new_reply ? '<a href="newpost.php?post='.$this->sanitize($_GET['topic'], 'integer').'" class="btn btn-success btn-mini">'.$this->lang['cl_admin_238'].'</a>' : '') : 
									($new_topic ? '<a href="newtopic.php?cat='.$this->sanitize($_GET['cat'], 'integer').'" class="btn btn-success btn-mini">'.$this->lang['cl_admin_239'].'</a>' : '')
								);
					}
					
					if(!empty($topic_details['tid'])){
						if($this->curpage == 'posts' && !empty($this->permissions['admin']) && $this->permissions['admin']){
							$button .= '<a href="editpost.php?post='.$this->sanitize($_GET['topic'], 'integer').'" class="btn btn-info btn-mini">'.$this->lang['cl_admin_272'].'</a>';
							
							$button .= '<button data-tokenname="edittopic_t" data-token="'.$this->csrfGenerate('edittopic_t').'" data-cid="'.$this->sanitize($topic_details['cid'], 'integer').'" data-tid="'.$this->sanitize($topic_details['tid'], 'integer').'" class="deletePost btn btn-danger btn-mini">'.$this->lang['cl_admin_271'].'</button>';
						}
					}
					
					$output .= '<div class="pull-left">
									<h3 class="post_title">'.$title.' '.$button.'</h3>
								</div>';
				}
			}
			
			if(!empty($pagination)){
				$output .= '<div id="ul_pagination" class="pull-right pagination forum_pagination">
								'.$pagination.'
							</div>';
			}
				
		$output .= '</div>';
		
		return $output;
	}
	
	public function generateBC($parent){
		$stmt = $this->sql->query('SELECT * FROM forum_categories ORDER BY c_sort ASC');
		$stmt->execute();
		
		// fetch all the categories
		$cats = array();
		while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
			$cats[$row['c_id']] = $row;
		}
		$stmt->closeCursor();
		
		// build the hierarchy
		$breadcrumb = array();
		while($parent != 0){
			array_unshift($breadcrumb, array('id'=>$cats[$parent]['c_id'], 'name'=>$cats[$parent]['c_name']));
			$parent = $cats[$parent]['c_sub'];
		}
		
		return $breadcrumb;
	}
	
	public function getTopic(){
		if(!$this->config['forum_enabled'] && (empty($this->permissions['admin']) || !$this->permissions['admin'])){
			return false;
		}
		
		$topic_id = $this->sanitize((!empty($_REQUEST['post']) ? $_REQUEST['post'] : 0), 'integer');
		if($topic_id > 0){
			$stmt = $this->sql->prepare('SELECT * FROM forum_posts WHERE p_id = ?');
			$stmt->execute(array($topic_id));
			$topic = $stmt->fetchAll(PDO::FETCH_ASSOC);
			$stmt->closeCursor();
		}
		
		if(empty($topic) || $topic_id == 0){
			header('Location: '.$_SERVER['HTTP_REFERER']);
			exit;
		}
		
		return $topic[0];
	}
	
	public function process_newtopic(){
		if(!$this->config['forum_enabled'] && (empty($this->permissions['admin']) || !$this->permissions['admin'])){
			return false;
		}
		
		if(!$this->csrfCheck('newtopic')){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_240']);
		}
		
		if(empty($_POST['c'])){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_241']);
		}
		
		if(empty($_POST['topic_title']) || empty($_POST['topic_body'])){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_242']);
		}
		
		if(strlen(preg_replace('/[^0-9A-Za-z]+/', '', $_POST['topic_body'])) < 10){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_243']);
		}
		
		$stmt = $this->sql->prepare('SELECT * FROM forum_categories WHERE c_id = ?');
		$stmt->execute(array($this->sanitize($_POST['c'], 'integer')));
		$catdetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		
		if($stmt->rowCount() < 1){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_244']);
		}
		
		$catdetails  = json_decode($catdetails[0]['c_permissions'], true);
		
		if(!in_array($_SESSION['udata']['membergroup'],$catdetails['topicperm'])){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_245']);
		}
		
		$data = array($this->sanitize($this->uid, 'integer'),
					  time(),
					  0,
					  1,
					  $this->sanitize($_POST['topic_title'], 'string'),
					  $this->sanitize($_POST['c'], 'integer'),
					  0,
					  $this->sanitize($_POST['topic_body'], 'string'),
					  (!empty($_POST['sticky']) && $_POST['sticky'] == 'true' ? 1 : 0),
					  (!empty($_POST['locked']) && $_POST['locked'] == 'true' ? 1 : 0)
		);
		
		$stmt = $this->sql->prepare('INSERT INTO forum_posts (p_author, p_postdate, p_replydate, p_istopic, p_topictitle, p_catid, p_topicid, p_post, p_sticky, p_locked) VALUES (?,?,?,?,?,?,?,?,?,?)');
		$stmt->execute($data);
		$err = $stmt->errorInfo();
		$stmt->closeCursor();
		$pid = $this->sql->lastInsertId();
		
		if($stmt->rowCount() > 0){
			$inc = $this->sql->prepare('UPDATE members SET f_posts=f_posts+1 WHERE id = ?');
			$inc->execute(array($this->uid));
			$inc->closeCursor();
			
			return array('status'=>true, 'id'=>$pid,'msg'=>'');
		}else{
			return array('status'=>false, 'id'=>0,'msg'=>$this->lang['cl_admin_246']);
		}
	}
	
	public function process_editpost($delete = false){
		if(!$this->config['forum_enabled'] && (empty($this->permissions['admin']) || !$this->permissions['admin'])){
			return false;
		}
		
		if(empty($this->permissions['admin']) || !$this->permissions['admin']){
			return false;
		}
		
		if(!$this->csrfCheck('edittopic_t') && !$this->csrfCheck('edittopic')){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_240']); 
		}
		
		if(empty($_POST['t'])){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_268']);
		}
		
		if(!$delete){
			if(strlen(preg_replace('/[^0-9A-Za-z]+/', '', $_POST['topic_body'])) < 10){
				return array('status'=>false, 'msg'=>$this->lang['cl_admin_243']);
			}
			
			$data = array($this->sanitize((!empty($_POST['topic_title']) ? $_POST['topic_title'] : ''), 'string'),
						  $this->sanitize($_POST['topic_body'], 'string'),
						  (!empty($_POST['sticky']) && $_POST['sticky'] == 'true' ? 1 : 0),
						  (!empty($_POST['locked']) && $_POST['locked'] == 'true' ? 1 : 0),
						  $this->sanitize($_POST['t'], 'integer')
			);
			
			$stmt = $this->sql->prepare('UPDATE forum_posts SET p_topictitle = ?,  p_post = ?, p_sticky = ?, p_locked = ? WHERE p_id = ?');
			$stmt->execute($data);
			$stmt->closeCursor();
		}else{
			$stmt = $this->sql->prepare('DELETE FROM forum_posts WHERE p_id = ?');
			$stmt->execute(array($this->sanitize($_POST['t'], 'integer')));
			$stmt->closeCursor();	
		}
		
		if($stmt->rowCount() > 0){			
			return array('status'=>true, 'id'=>$this->sanitize((empty($_POST['tcp']) ? $_POST['t'] : $_POST['tcp']), 'integer'),'msg'=>'');
		}else{
			if(!$delete){
				return array('status'=>false, 'id'=>0,'msg'=>$this->lang['cl_admin_269']);
			}else{
				return array('status'=>false, 'id'=>0,'msg'=>$this->lang['cl_admin_270']);
			}
		}
	}
	
	public function process_newreply(){
		if(!$this->config['forum_enabled'] && (empty($this->permissions['admin']) || !$this->permissions['admin'])){
			return false;
		}
		
		if(!$this->csrfCheck('newpost')){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_247']);
		}
		
		if(empty($_POST['t'])){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_248']);
		}
		
		if(strlen(preg_replace('/[^0-9A-Za-z]+/', '', $_POST['topic_body'])) < 10){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_249']);
		}
		
		$_POST['t'] = $this->sanitize($_POST['t'], 'integer');
		
		$stmt = $this->sql->prepare('SELECT c_permissions FROM forum_categories WHERE c_id = ?');
		$stmt->execute(array($this->sanitize($_POST['c'], 'integer')));
		$catdetails = $stmt->fetchAll(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		
		if($stmt->rowCount() < 1){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_250']);
		}
		
		$catdetails  = json_decode($catdetails[0]['c_permissions'], true);
		
		if(!in_array($_SESSION['udata']['membergroup'],$catdetails['topicperm'])){
			return array('status'=>false, 'msg'=>$this->lang['cl_admin_251']);
		}
		
		$data = array($this->sanitize($this->uid, 'integer'),
					  time(),
					  0,
					  $this->sanitize($_POST['c'], 'integer'),
					  $_POST['t'],
					  $this->sanitize($_POST['topic_body'], 'string'),
					  (!empty($_POST['sticky']) && $_POST['sticky'] ? true : false),
					  (!empty($_POST['locked']) && $_POST['locked'] ? true : false)
		);
		
		$stmt = $this->sql->prepare('INSERT INTO forum_posts (p_author, p_postdate, p_istopic, p_catid, p_topicid, p_post, p_sticky, p_locked) VALUES (?,?,?,?,?,?,?,?)');
		$stmt->execute($data);
		$err = $stmt->errorInfo();
		$stmt->closeCursor();
		$pid = $this->sql->lastInsertId();
		
		if($stmt->rowCount() > 0){
			$stmt = $this->sql->prepare('UPDATE forum_posts SET p_replydate = ? WHERE p_id = ?');
			$stmt->execute(array(time(), $_POST['t']));
			$stmt->closeCursor();
			
			$inc = $this->sql->prepare('UPDATE members SET f_posts=f_posts+1 WHERE id = ?');
			$inc->execute(array($this->uid));
			$inc->closeCursor();
			
			return array('status'=>true, 'id'=>$_POST['t'], 'pid'=>$pid,'msg'=>'');
		}else{
			return array('status'=>false, 'id'=>0,'msg'=>$this->lang['cl_admin_252']);
		}
	}
	
	public function wysiwyg_show($id = ''){
		return '<link rel="stylesheet" href="assets/sceditor/minified/themes/default.min.css" type="text/css" media="all" />
				<script src="assets/sceditor/minified/jquery.sceditor.min.js"></script>
				<script src="assets/sceditor/languages/en.js"></script>
				<script>
					$(document).ready(function() {
						$("'.(!empty($id) ? '#'.$id : 'textarea').'").sceditor({toolbar: "bold,italic,underline,strike,subscript,superscript|left,center,right,justify|font,size,color,removeformat|cut,copy,paste,pastetext|bulletlist,orderedlist|table|code,quote|horizontalrule,image,link,unlink|emoticon,date,time|ltr,rtl|source"});
					});
				</script>';
	}
}