<div class="wrap">
	<form method="post" action="options-general.php?page=integrations" novalidate="novalidate">
		<h1>Integrations</h1>
		<h2 class="title" id="facebook">Facebook</h2>
		<p>Just some key details about your Facebook app. Select one app from <a href="https://developers.facebook.com/apps/" target="_blank">your app listings</a> and fill out the details below.</p>
		<table class="form-table" role="presentation">
			<tbody>
				<tr>
					<th scope="row">App ID</th>
					<td><input name="facebook_app_id" type="text" id="app-id" value="<?php echo $facebook_app_id; ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row">App Secret</th>
					<td><input name="facebook_app_secret" type="text" id="app-secret" value="<?php echo $facebook_app_secret; ?>" class="regular-text"></td>
				</tr>
			</tbody>
		</table>
		<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes"></p>
	</form>
</div>