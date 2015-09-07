<?php
/*****************************************************************************************
 * Solid PHP User Management System														 *
 * Copyright 2012 Mark Eliasen (MrEliasen)												 *
 *																						 *
 * CodeCanyon Link: http://codecanyon.net/item/solid-php-user-management-system-/1254295 *
 * Author Website: http://zolidweb.com													 *
 * Version: 1.4.0 																		 *
 *****************************************************************************************/
 
require_once('system/initiater.php');
$site->restricted_page('login.php');

$topic = $site->getTopic();
	
$site->template_show_header();
?>
<div class="row">
    <div class="span12">
		<h1>New Reply</h1>
	</div>
</div>

<div class="row">

	<form action="#" id="newtopic">
		<div class="span12">
			<textarea id="wysiwyg" name="topic_body" class="span12" rows="20"></textarea>
		</div>
		
		<div class="span12">
			<div class="form-actions">
				<button data-forum="newreply" type="submit" class="btn btn-success span2">Post Topic</button>
			</div>
		</div>
		
		<input type="hidden" name="t" value="<?php echo $topic['p_id']; ?>">
		<input type="hidden" name="c" value="<?php echo $topic['p_catid']; ?>">
		<input type="hidden" name="newpost" value="<?php echo $site->csrfGenerate('newpost'); ?>" />
	</form>
	
	<?php echo $site->showPosts(false); ?>
	
	<script>
		$(document).ready(function() {
			$("button").button();
		});
	</script>
	<?php echo $site->wysiwyg_show('wysiwyg'); ?>
	
</div>
<?php
$site->template_show_footer();