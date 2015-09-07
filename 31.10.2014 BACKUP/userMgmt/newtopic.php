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

$cat_id = $site->sanitize((!empty($_GET['cat']) ? $_GET['cat'] : 0), 'integer');
?>
<div class="row">
    <div class="span12">
		<h1>New Topic</h1>
	</div>
</div>

<div class="row">
	<?php
		if($cat_id > 0):
	?>
		<form action="#" id="newtopic">
			<div class="span12">
				<div class="control-group">
					<label class="control-label" for="topic_title">Topic Title</label>
					<div class="controls">
						<input type="text" name="topic_title" id="topic_title" class="input-xxlarge" placeholder="Topic title">
					</div>
				</div>
				
				<div class="clearfix"></div>
				
				<textarea id="wysiwyg" name="topic_body" class="span12" rows="20"></textarea>
			</div>
			
			<div class="span12">
				<div class="form-actions">
					<?php
					if($site->permissions['admin']):
					?>
						<div class="btn-group pull-left" data-toggle="buttons-checkbox">
							<button id="stickyTopic" class="btn btn-info">Sticky Topic</button>
							<button id="lockedTopic" class="btn btn-warning">Lock Topic</button>
						</div>
					<?php
					endif;
					?>
					<button type="submit" class="pull-right btn btn-success span2">Post Topic</button>
				</div>
			</div>
			<input type="hidden" name="c" value="<?php echo $cat_id; ?>">
			<input type="hidden" name="newtopic" value="<?php echo $site->csrfGenerate('newtopic'); ?>">
		</form>
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