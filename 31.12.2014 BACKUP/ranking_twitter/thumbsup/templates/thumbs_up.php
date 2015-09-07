<?php defined('THUMBSUP_DOCROOT') or exit('No direct script access');
/**
 * ThumbsUp
 *
 * @author     Geert De Deckere <geert@idoe.be>
 * @link       http://geertdedeckere.be/shop/thumbsup/
 * @copyright  Copyright 2009-2010
 */
?>

<form method="post" id="thumbsup_<?php echo $item->id ?>" class="thumbsup <?php echo $template ?> <?php if ($item->closed) echo 'closed' ?> <?php if ($item->user_voted) echo 'user_voted' ?> <?php if ($item->closed OR $item->user_voted) echo 'disabled' ?> <?php echo $options->align ?>" name="<?php echo $template ?>">
	<input type="hidden" name="thumbsup_id" value="<?php echo $item->id ?>" />
	<input type="hidden" name="thumbsup_format" value="<?php echo htmlspecialchars($item->format) ?>" />

	<strong class="result1 error <?php if (strlen($item->result[0]) > 4) echo 'squeeze' ?>"><?php echo htmlspecialchars($item->result[0]) ?></strong>

	<input type="submit" name="thumbsup_vote" value="+1" <?php if ($item->closed OR $item->user_voted) echo 'disabled="disabled"' ?> />
</form>
