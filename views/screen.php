<div id="screen-options-wrap" class="hidden" tabindex="-1" aria-label="Screen Options Tab" style="display: block;">
	<fieldset class="metabox-prefs">
		<legend>Integration options</legend>

		<?php if( isset($facebook_integration) && ( $facebook_integration==='required' || $facebook_integration==='optional' ) ): ?>
		<label>
			<input class="hide-column-tog" name="facebook_integration" type="checkbox" id="facebook-show" value="facebook" <?php echo (get_option('facebook_integration','no')=='yes' ? 'checked="checked"' : ''); ?> <?php echo ( (isset($facebook_integration) && $facebook_integration==='required') ? 'disabled="disabled"' : '' ); ?> >
			Facebook
		</label>
		<?php endif; ?>

		<?php if( isset($google_integration) && ( $google_integration==='required' || $google_integration==='optional' ) ): ?>
		<label>
			<input class="hide-column-tog" name="google_integration" type="checkbox" id="google-show" value="google" <?php echo (get_option('google_integration','no')=='yes' ? 'checked="checked"' : ''); ?> <?php echo ( (isset($google_integration) && $google_integration==='required') ? 'disabled="disabled"' : '' ); ?> >
			Google
		</label>
		<?php endif; ?>

	</fieldset>
	<fieldset class="metabox-prefs">
		<legend>User-specific preferences</legend>
		<label><input class="hide-column-tog" type="checkbox" id="show-basic-helpers" <?php echo (get_user_meta( get_current_user_id(), 'show_basic_helpers', true )=='yes' ? 'checked="checked"' : ''); ?>>Show basic helpers</label>
		<label><input class="hide-column-tog" type="checkbox" id="show-developer-helpers" <?php echo (get_user_meta( get_current_user_id(), 'show_developer_helpers', true )=='yes' ? 'checked="checked"' : ''); ?>>Show developer helpers</label>
	</fieldset>
</div>