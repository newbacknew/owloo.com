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

	<?php if (max(strlen($item->result[0]), strlen($item->result[1])) > 5) $squeeze = TRUE ?>
	<strong class="result1 error <?php if ( ! empty($squeeze)) echo 'squeeze' ?>" title="Votes up"><?php   echo htmlspecialchars($item->result[0]) ?></strong>
	<strong class="result2 error <?php if ( ! empty($squeeze)) echo 'squeeze' ?>" title="Votes down"><?php echo htmlspecialchars($item->result[1]) ?></strong>

	<input class="up tooltip"   name="thumbsup_vote" type="submit" value="+1" title="Me gusta"   <?php if ($item->closed OR $item->user_voted) echo 'disabled="disabled"' ?> />
	<input class="down tooltip" name="thumbsup_vote" type="submit" value="-1" title="No me gusta" <?php if ($item->closed OR $item->user_voted) echo 'disabled="disabled"' ?> />
</form>
