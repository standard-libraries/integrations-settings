<div class="wrap">
	<form method="post" action="options-general.php?page=integrations" novalidate="novalidate">
		<div class="action-hook-container"><?php do_action('integration_header'); ?>
			<button type="button" class="button button-secondary action-hook-revealer" data-code="add_action('integration_header',function(){});"><span class="dashicons dashicons-editor-code"></span></button>
		</div>
		<h1>Integrations</h1>
		<div class="action-hook-container"><?php do_action('facebook_integration_before_header'); ?>
			<button type="button" class="button button-secondary action-hook-revealer" data-code="add_action('facebook_integration_before_header',function(){});"><span class="dashicons dashicons-editor-code"></span></button>
		</div>
		<div id="facebook-section">
		<h2 class="title" id="facebook">Facebook</h2>
		<div class="action-hook-container"><?php do_action('facebook_integration_after_header'); ?>
			<button type="button" class="button button-secondary action-hook-revealer" data-code="add_action('facebook_integration_after_header',function(){});"><span class="dashicons dashicons-editor-code"></span></button>
		</div>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row">App ID</th>
					<td>
						<input name="facebook_app_id" type="text" id="facebook-app-id" value="<?php echo $facebook_app_id; ?>" class="regular-text">
						<button type="button" class="button button-secondary copy-clipboard" data-target="facebook-app-id" title="Copy to clipboard"><span class="dashicons dashicons-clipboard"></span></button>
						<button type="button" class="button button-secondary php-code-revealer" data-code="get_option('facebook_app_id')" data-description="Facebook's App ID"><span class="dashicons dashicons-editor-code"></span></button>
						<p class="description">Retrieve this value from <a href="https://developers.facebook.com/apps/" target="_blank">your list of facebook apps</a>.</p>
					</td>
				</tr>
				<?php if( isset($facebook_app_id) && !empty($facebook_app_id) ): ?>
				<tr>
					<th scope="row">App Secret</th>
					<td>
						<input name="facebook_app_secret" type="text" id="facebook-app-secret" value="<?php echo $facebook_app_secret; ?>" class="regular-text">
						<button type="button" class="button button-secondary copy-clipboard" data-target="facebook-app-secret" title="Copy to clipboard"><span class="dashicons dashicons-clipboard"></span></button>
						<button type="button" class="button button-secondary php-code-revealer" data-code="get_option('facebook_app_secret')" data-description="Facebook's App Secret"><span class="dashicons dashicons-editor-code"></span></button>
					<p class="description">Retrieve this value from
					<?php if( isset($facebook_app_id) && !empty($facebook_app_id) ): ?>
					<a href="https://developers.facebook.com/apps/<?php echo $facebook_app_id; ?>/settings/basic/" target="_blank">your facebook app's basic settings</a>.
					<?php else: ?>
					<a href="https://developers.facebook.com/apps/" target="_blank">your list of facebook apps</a>.
					<?php endif; ?>
					</p>
					</td>
				</tr>
				<tr>
					<th scope="row">App Domains</th>
					<td>
						<input type="text" id="facebook-app-domains" value="<?php echo $hostname; ?>" class="regular-text" readonly="readonly">
						<button type="button" class="button button-secondary copy-clipboard" data-target="facebook-app-domains" title="Copy to clipboard"><span class="dashicons dashicons-clipboard"></span></button>
						<button type="button" class="button button-secondary php-code-revealer" data-code="get_option('facebook_app_domains')" data-description="Facebook's App Secret"><span class="dashicons dashicons-editor-code"></span></button>
					<?php if( !$is_localhost ): ?>
					<p class="description">
						<b>Copy</b> and <b>paste</b> this value into <a href="https://developers.facebook.com/apps/<?php echo $facebook_app_id; ?>/settings/basic/" target="_blank">your facebook app's basic settings</a>.
					</p>
					<?php else: ?>
					<p class="description">Note: Integrations do not work with Wordpress installations on the localhost.</p>
					<?php endif; ?>
					</td>
				</tr>

				<?php endif; ?>
			</tbody>
		</table>
		</div>
		<?php if( isset($facebook_app_id) && !empty($facebook_app_id) ): ?>
		<div class="action-hook-container"><?php do_action('facebook_integration_footer'); ?>
			<button type="button" class="button button-secondary action-hook-revealer" data-code="add_action('facebook_integration_footer',function(){});"><span class="dashicons dashicons-editor-code"></span></button>
		</div>
		<?php endif; ?>

		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>