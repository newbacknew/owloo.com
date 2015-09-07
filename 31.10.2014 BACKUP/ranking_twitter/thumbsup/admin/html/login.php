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

</head>
<body class="login">

	<noscript>
		<p class="center"><strong>The ThumbsUp admin area requires JavaScript to be enabled.</strong></p>
	</noscript>

	<form id="login" method="post">

		<h1>ThumbsUp Admin</h1>

		<?php if ( ! empty($error)) { ?>
			<div class="error"><?php echo htmlspecialchars($error) ?></div>
		<?php } ?>

		<p>
			<label for="username">Username:</label>
			<input id="username" name="username" type="text" value="<?php if (isset($username)) echo htmlspecialchars($username) ?>" />
		</p>
		<p>
			<label for="password">Password:</label>
			<input id="password" name="password" type="password" />
		</p>
		<p class="submit">
			<button type="submit">Login</button>
		</p>

	</form>

</body>
</html>