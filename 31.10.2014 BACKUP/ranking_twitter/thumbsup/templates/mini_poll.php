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

	<span class="graph up"   style="width:<?php echo $item->votes_pct_up ?>%;   background-color:<?php echo $options->color_up   ?>"></span>
	<span class="graph down" style="width:<?php echo $item->votes_pct_down ?>%; background-color:<?php echo $options->color_down ?>"></span>

	<label class="option_up" for="thumbsup_<?php echo $item->id ?>_up">
		<input id="thumbsup_<?php echo $item->id ?>_up" type="radio" name="thumbsup_vote" value="+1" />
		<?php echo htmlspecialchars($options->up) ?>
		<strong class="result1 error"><?php echo htmlspecialchars($item->result[0]) ?></strong>
	</label>

	<label class="option_down" for="thumbsup_<?php echo $item->id ?>_down">
		<input id="thumbsup_<?php echo $item->id ?>_down" type="radio" name="thumbsup_vote" value="-1" />
		<?php echo htmlspecialchars($options->down) ?>
		<strong class="result2 error"><?php echo htmlspecialchars($item->result[1]) ?></strong>
	</label>

	<input type="submit" value="Vote" <?php if ($item->closed OR $item->user_voted) echo 'disabled="disabled"' ?> />
</form>
