<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    NAMI_MD_Post_Meta
 * @subpackage NAMI_MD_Post_Meta/admin/partials
 */
?>

<h2>Bulk update WPDM post meta data: </h2>

<div class="wrap">
<form action="<?php echo admin_url( 'admin-post.php' ); ?>" method="post">
	<?php
	if ( function_exists( 'wp_nonce_field' ) ) {
		wp_nonce_field( 'nami_md_post_meta_submit' );
	}
	?>
	<input type="hidden" name="action" value="nami_md_post_meta_submit">
	<input type="submit" value="Update">
</form>
</div>
