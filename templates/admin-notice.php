<?php // docs: https://developer.wordpress.org/reference/hooks/admin_notices/ ?>
<div class="notice <?php v( $var['class'] ) ?>">
	<p><?php v( $var['message'], 'default("Missing Message")', 'safe_html', 'raw' ) ?></p>
</div>
