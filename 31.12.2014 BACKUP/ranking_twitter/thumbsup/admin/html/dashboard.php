<?php defined('THUMBSUP_DOCROOT') or exit('No direct script access.');
/**
 * ThumbsUp
 *
 * @author     Geert De Deckere <geert@idoe.be>
 * @link       http://geertdedeckere.be/shop/thumbsup/
 * @copyright  Copyright 2009-2010
 */
?>
<!DOCTYPE html>

<html lang="en">
<head>

	<meta charset="utf-8" />
	<title>ThumbsUp Admin</title>

	<link rel="stylesheet" href="<?php echo ThumbsUp::config('url').'admin/css/admin.css' ?>" />

	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
	<script>
	$(document).ready(function() {

		// Total items found count
		var $total_items = $('#total_items');

		// Spinner image
		var spinner = '<img class="spinner" alt="" src="<?php echo ThumbsUp::config('url').'images/spinner_small.gif' ?>" />';

		// Auto-submit pagination forms
		$('#page, #items_per_page').change(function() {
			$(this).closest('form').submit();
		});

		// Delete an item
		$('a.delete').click(function() {
			var $this = $(this),
				$row = $this.closest('tr');

			// Show a spinner
			$this.html(spinner);

			// Extra confirmation
			if ( ! confirm('Sure you want to delete “' + $row.find(':input[name=name_original]').val() + '”?\n\nThis cannot be undone.'))
			{
				$this.html('&#9747;').blur();
				return false;
			}

			// Actually start the delete request
			$.ajax({
				type: 'POST',
				url: '<?php echo ThumbsUp_Admin::url('action=delete') ?>',
				cache: false,
				dataType: 'json',
				timeout: 15000,

				data: {
					id: $row.find(':input[name=id]').val()
				},

				error: function(XMLHttpRequest, textStatus) {
					alert(textStatus);
				},

				success: function(data) {
					$row.remove();
					$total_items.text($total_items.text() - 1);
				}
			});

			return false;
		});

		// Start editing an item
		$('a.edit').click(function() {
			var $this = $(this),
				$row = $this.closest('tr');

			// Enable saving controls
			$this.closest('div').hide().siblings('div').show();

			// Enable all input and focus on the name
			$row.addClass('editing').find(':input').removeAttr('disabled').filter('[name="name"]').focus();
			return false;
		});

		// Save an edited item
		$(':submit.save').not(':disabled').click(function() {
			var $this = $(this),
				$row = $this.closest('tr');

			// Remove all errors
			$row.find('td.error').removeClass('error').find('div.error').remove();

			// Show spinner and hide "cancel" link
			$this.attr('disabled', 'disabled').siblings('span').hide().after(spinner);

			// All data that could be sent (the id is required)
			var data = {
				id: $row.find(':input[name=id]').val(),
				closed: $row.find(':input[name=closed]').val(),
				name: $row.find(':input[name=name]').val(),
				votes_up: $row.find(':input[name=votes_up]').val(),
				votes_down: $row.find(':input[name=votes_down]').val()
			};

			// The votes_up and votes_down values are only sent if they were changed,
			// this prevents new votes that were cast in the mean time to be overridden.
			if (data.votes_up == $row.find(':input[name=votes_up_original]').val() && data.votes_down == $row.find(':input[name=votes_down_original]').val()) {
				delete data.votes_up;
				delete data.votes_down;
			}

			$.ajax({
				type: 'POST',
				url: '<?php echo ThumbsUp_Admin::url('action=edit') ?>',
				cache: false,
				dataType: 'json',
				timeout: 15000,
				data: data,

				error: function(XMLHttpRequest, textStatus) {
					alert(textStatus);
				},

				success: function(data) {
					if ('errors' in data) {
						// Unless somebody has been messing with the POST values,
						// this item has been deleted earlier.
						if ('id' in data.errors) {
							alert('The item you wanted to edit does not exist anymore.');
							$row.remove();
							$total_items.text($total_items.text() - 1);
							return;
						}

						// Normal error messages for input fields
						if ('name' in data.errors) {
							$row.find(':input[name=name]').after('<div class="error">' + data.errors.name + '</div>').closest('td').addClass('error');
						}
						if ('votes_up' in data.errors) {
							$row.find(':input[name=votes_up]').after('<div class="error">' + data.errors.votes_up + '</div>').closest('td').addClass('error');
						}
						if ('votes_down' in data.errors) {
							$row.find(':input[name=votes_down]').after('<div class="error">' + data.errors.votes_down + '</div>').closest('td').addClass('error');
						}

						// Add focus to first erroneous field
						$row.find('td.error:first :input').focus();
						return;
					}

					// No errors, the item has been updated successfully
					// Show "edit" link again and disable input fields
					$this.closest('div').hide().siblings('div').show();
					$row.find(':input').not($this).attr('disabled', 'disabled');
					$row.removeClass('editing');

					// Update all fields with the clean updated values
					$row.find('.closed span').text((data.item.closed) ? 'no' : 'yes');
					$row.find(':input[name=closed], :input[name=closed_original]').val(data.item.closed);
					$row.find(':input[name=name], :input[name=name_original]').val(data.item.name);
					$row.find(':input[name=votes_up], :input[name=votes_up_original]').val(data.item.votes_up);
					$row.find(':input[name=votes_down], :input[name=votes_down_original]').val(data.item.votes_down);
					$row.find('.total').text(data.item.votes_total);
					$row.find('.balance').text(((data.item.votes_balance > 0) ? '+' : '') + data.item.votes_balance);
					$row.find('.pct_up').text(Math.round(data.item.votes_pct_up) + '%');
					$row.find('.pct_down').text(Math.round(data.item.votes_pct_down) + '%');
				},

				complete: function() {
					// Re-enable the button, remove the spinner and show cancel link again
					$this.removeAttr('disabled').siblings('span').show().siblings('.spinner').remove();
				}
			});
		});

		// Cancel editing
		$('a.cancel').click(function() {
			var $this = $(this),
				$row = $this.closest('tr');

			$row.removeClass('editing');

			// Remove all errors
			$row.find('td.error').removeClass('error').find('div.error').remove();

			// Disable input fields again and refill them with the original values
			$row.find(':input').attr('disabled', 'disabled');
			$row.find(':input[name=closed]').val($row.find(':input[name=closed_original]').val());
			$row.find(':input[name=name]').val($row.find(':input[name=name_original]').val());
			$row.find(':input[name=votes_up]').val($row.find(':input[name=votes_up_original]').val());
			$row.find(':input[name=votes_down]').val($row.find(':input[name=votes_down_original]').val());

			// Show the "edit" link again
			$this.closest('div').hide().siblings('div').show();

			return false;
		});

	});
	</script>
</head>
<body>

	<noscript>
		<p class="center"><strong>The ThumbsUp admin area requires JavaScript to be enabled.</strong></p>
	</noscript>

	<div id="header">
		<div id="admin-header">
			<h1><a href="<?php echo ThumbsUp_Admin::url(NULL, FALSE) ?>">ThumbsUp Admin</a></h1>

			<ul>
				<li><a href="<?php echo ThumbsUp_Admin::url('action=logout', FALSE) ?>">Logout</a></li>
				<li><a href="http://www.geertdedeckere.be/shop/thumbsup/help/">Help</a></li>
			</ul>
		</div>

		<form id="items-header" method="get">
			<?php foreach ($_GET as $key => $value) { if (strpos($key, 'filter_') === 0 OR $key === 'page') continue ?>
				<input type="hidden" name="<?php echo htmlspecialchars($key) ?>" value="<?php echo htmlspecialchars($value) ?>" />
			<?php } ?>
			<table>
				<col class="closed" width="80" />
				<col class="name" width="auto" />
				<col class="up" width="80" />
				<col class="down" width="80" />
				<col class="total" width="80" />
				<col class="balance" width="80" />
				<col class="pct_up" width="80" />
				<col class="pct_down" width="80" />
				<col class="date" width="120" />
				<col class="action" width="120" />
				<tr>
					<th class="closed center">Open</th>
					<th class="name left extra-padding">Name</th>
					<th class="up center">Up</th>
					<th class="down center">Down</th>
					<th class="total center">Total</th>
					<th class="balance center">Balance</th>
					<th class="pct_up center">% Up</th>
					<th class="pct_down center">% Down</th>
					<th class="date center">Date</th>
					<th class="action left"></th>
				</tr>
				<tr>
					<td class="closed center">
						<select name="filter_closed">
							<option value=""></option>
							<option value="0" <?php if ($filter_closed === 0) echo 'selected="selected"' ?>>yes</option>
							<option value="1" <?php if ($filter_closed === 1) echo 'selected="selected"' ?>>no</option>
						</select>
					</td>
					<td class="name left extra-padding">
						<input class="stretch" name="filter_name" type="text" value="<?php echo htmlspecialchars($filter_name) ?>" />
					</td>
					<td class="up center">
						<button type="submit">Search</button>
					</td>
					<td class="down center"></td>
					<td class="total center"></td>
					<td class="balance center"></td>
					<td class="pct_up center"></td>
					<td class="pct_down center"></td>
					<td class="date center"></td>
					<td class="action left"></td>
				</tr>
			</table>
		</form>
	</div><!-- #header -->

	<?php if (empty($items)) { ?>
		<p id="nothing-found">No items found</p>
	<?php } else { ?>
		<table id="items">
			<col class="closed" width="80" />
			<col class="name" width="auto" />
			<col class="up" width="80" />
			<col class="down" width="80" />
			<col class="total" width="80" />
			<col class="balance" width="80" />
			<col class="pct_up" width="80" />
			<col class="pct_down" width="80" />
			<col class="date" width="120" />
			<col class="action" width="120" />
			<?php foreach ($items as $id => $item) { $i = (empty($i)) ? 1 : 0 ?>
				<tr class="<?php echo empty($i) ? 'alt2' : 'alt1' ?>">
					<td class="closed center">
						<input type="hidden" name="closed_original" value="<?php echo $item->closed ?>" />
						<span><?php echo ($item->closed) ? 'no' : 'yes' ?></span>
						<select name="closed" disabled="disabled">
							<option value="0" <?php if ( ! $item->closed) echo 'selected="selected"' ?>>yes</option>
							<option value="1" <?php if ($item->closed) echo 'selected="selected"' ?>>no</option>
						</select>
					</td>
					<td class="name left extra-padding">
						<input type="hidden" name="id" value="<?php echo $item->id ?>" />
						<input type="hidden" name="name_original" value="<?php echo htmlspecialchars($item->name) ?>" />
						<input class="stretch" name="name" type="text" value="<?php echo htmlspecialchars($item->name) ?>" disabled="disabled" maxlength="255" />
					</td>
					<td class="up center extra-padding">
						<input type="hidden" name="votes_up_original" value="<?php echo $item->votes_up ?>" />
						<input class="stretch center" name="votes_up" type="text" size="4" value="<?php echo $item->votes_up ?>" disabled="disabled" maxlength="10" />
					</td>
					<td class="down center extra-padding">
						<input type="hidden" name="votes_down_original" value="<?php echo $item->votes_down ?>" />
						<input class="stretch center" name="votes_down" type="text" size="4" value="<?php echo $item->votes_down ?>" disabled="disabled" maxlength="10" />
					</td>
					<td class="total center"><?php echo $item->votes_total ?></td>
					<td class="balance center"><?php echo ($item->votes_balance > 0) ? '+' : '', $item->votes_balance ?></td>
					<td class="pct_up center"><?php echo round($item->votes_pct_up) ?>%</td>
					<td class="pct_down center"><?php echo round($item->votes_pct_down) ?>%</td>
					<td class="date center"><?php echo date('j M Y', $item->date) ?></td>
					<td class="action left">
						<div class="edit">
							<a class="edit" href="#">Edit</a> <a class="delete" href="#" title="Delete">&#9747;</a>
						</div>
						<div class="save">
							<button class="save">Save</button> <span>or <a class="cancel" href="#">cancel</a></span>
						</div>
					</td>
				</tr>
			<?php } ?>
		</table>
	<?php } ?>

	<div id="footer">
		<div>
			<form method="get">
				<?php foreach ($_GET as $key => $value) { if ($key === 'page') continue ?>
					<input type="hidden" name="<?php echo htmlspecialchars($key) ?>" value="<?php echo htmlspecialchars($value) ?>" />
				<?php } ?>
				<label for="page">Page</label>
				<a id="prev" rel="prev" title="Previous page" href="<?php echo ThumbsUp_Admin::url('page='.($page - 1)) ?>" <?php if ($page <= 1) echo 'style="visibility:hidden"' ?>>‹</a>
				<input id="page" name="page" type="number" size="2" min="1" value="<?php echo $page ?>" <?php if ($total_pages < 2) echo 'disabled="disabled"' ?> />
				of <?php echo $total_pages ?>
				<a id="next" rel="next" title="Next page" href="<?php echo ThumbsUp_Admin::url('page='.($page + 1)) ?>" <?php if ($page >= $total_pages) echo 'style="visibility:hidden"' ?>>›</a>
			</form>
		</div>

		<div>
			<form method="get">
				<?php foreach ($_GET as $key => $value) { if ($key === 'items_per_page') continue ?>
					<input type="hidden" name="<?php echo htmlspecialchars($key) ?>" value="<?php echo htmlspecialchars($value) ?>" />
				<?php } ?>
				<label for="items_per_page">View</label>
				<select id="items_per_page" name="items_per_page" class="center">
					<?php foreach ($items_per_page_select as $i) { ?>
						<option value="<?php echo $i ?>" <?php if ($i === $items_per_page) echo 'selected="selected"' ?>>
							<?php echo ($i === 0) ? 'all' : $i ?>
						</option>
					<?php } ?>
				</select>
				per page
			</form>
		</div>

		<div>
			Total <strong id="total_items"><?php echo $total_items ?></strong> items found
		</div>

		<div class="credits">
			Powered by <a href="http://codecanyon.net/item/thumbsup/50411?ref=GeertDD">ThumbsUp v<?php echo ThumbsUp::VERSION ?></a><br />
			©2009–2010 <a href="http://codecanyon.net/user/GeertDD?ref=GeertDD">Geert De Deckere</a>
		</div>
	</div>

</body>
</html>