<?php // docs: https://developer.wordpress.org/reference/hooks/admin_notices/ ?>
<div class="notice <?= v( $var['class'] ) ?>">
	<p><?= v( $var['message'], 'default("Missing Message")', 'safe_html', 'raw' ) ?></p>
</div>
