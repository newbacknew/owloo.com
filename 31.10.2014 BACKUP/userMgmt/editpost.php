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
	
$site->template_show_header();

$topic = $site->getTopic();
?>
<div class="row">
    <div class="span12">
		<h1>New Topic</h1>
	</div>
</div>

<div class="row">
	<?php
		if(!empty($topic)):
	?>
		<form action="#" id="newtopic">
			<div class="span12">
				<?php
				if($topic['p_istopic']):
				?>
				<div class="control-group">
					<label class="control-label" for="topic_title">Topic Title</label>
					<div class="controls">
						<input type="text" name="topic_title" id="topic_title" class="input-xxlarge" placeholder="Topic title" value="<?php echo $topic['p_topictitle']; ?>">
					</div>
				</div>
				
				<div class="clearfix"></div>
				<?php
				endif
				?>
				<textarea id="wysiwyg" name="topic_body" class="span12" rows="20"><?php echo $topic['p_post']; ?></textarea>
			</div>
			
			<div class="span12">
				<div class="form-actions">
					<?php
					if($topic['p_istopic']):
					?>
					<div class="btn-group pull-left" data-toggle="buttons-checkbox">
						<button id="stickyTopic" class="btn btn-info <?php echo ($topic['p_sticky'] ? 'active' : ''); ?>">Sticky Topic</button>
						<button id="lockedTopic" class="btn btn-warning <?php echo ($topic['p_locked'] ? 'active' : ''); ?>">Lock Topic</button>
					</div>
					<?php
					endif;
					?>
					<button type="submit" class="pull-right btn btn-success span2">Update Post</button>
				</div>
			</div>
			<input type="hidden" name="t" value="<?php echo $topic['p_id']; ?>">
			<input type="hidden" name="tcp" value="<?php echo $topic['p_topicid']; ?>">
			<input type="hidden" name="edittopic" value="<?php echo $site->csrfGenerate('edittopic'); ?>">
		</form>
		<input type="hidden" name="topiccall" value="editpost">
		<script>
			$(document).ready(function() {
				$("button").button();
			});
		</script>
		<?php echo $site->wysiwyg_show('wysiwyg'); ?>
	<?php
		endif;
	?>
	
</div>
<?php
$site->template_show_footer();