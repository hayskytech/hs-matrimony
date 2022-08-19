<?php
$user_id = get_current_user_id();
if (!$user_id) {
	?>
	<h3>Please Login with Agent Account.</h3>
	<form name="loginform" id="loginform" action="<?php echo site_url(); ?>/wp-login.php" method="post">
	<table>
		<tr>
			<td><label for="user_login">Username or Email Address</label></td>
			<td>
			<input type="text" name="log" id="user_login" autocomplete="username" class="input" value="" size="20">
			</td>
		</tr>
		<tr>
			<td><label for="user_pass">Password</label>	</td>
			<td>
				<input type="password" name="pwd" id="user_pass" autocomplete="current-password" class="input" value="" size="20">
			</td>
		</tr>
		<tr>
			<td></td>
			<td><label><input name="rememberme" type="checkbox" id="rememberme" value="forever" checked="checked"> Remember Me</label></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="wp-submit" id="wp-submit" class="button button-primary" value="Log In"></td>
		</tr>
	</table>
	<input type="hidden" name="redirect_to" value="<?php echo get_permalink(); ?>">
	</form>
	<?php
} elseif (current_user_can('agent')) {
	if (isset($_GET['add-user'])) {
		?>
		<form method="post" action="<?php echo get_permalink(); ?>" class="ui form">
			<table class="ui collapsing striped table">
				<tr>
					<td>Name</td>
					<td><input type="text" name="display_name"></td>
				</tr>
				<tr>
					<td>Phone Number</td>
					<td><input type="text" name="phone"></td>
				</tr>
				<tr>
					<td>Password</td>
					<td><input type="password" name="pwd"></td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="submit" class="ui green button" value="Submit"></td>
				</tr>
			</table>
		</form>
		<script type="text/javascript">
		if ( window.history.replaceState ) {
			window.history.replaceState( null, null, window.location.href );
		}
		</script>
		<?php
	} else {
		if (isset($_POST["submit"])) {
			$userdata['user_login'] = $_POST['phone'];
			$userdata['user_pass'] = $_POST['pwd'];
			$userdata['display_name'] = $_POST['display_name'];
			$userdata['first_name'] = $_POST['display_name'];
			$ID = wp_insert_user( $userdata );
			update_user_meta($ID, 'agent', $user_id);
			echo 'User inserted';
		}
		$args = array(
			'meta_key' => 'agent',
			'meta_value' => $user_id
		);
		$users = get_users($args);
		if (count($users)) {
			?>
			<h2>My Users</h2>
			<table>
				<tr>
					<th>ID</th>
					<th>Name</th>
					<th>Phone</th>
				</tr>
				<?php foreach ($users as $user) { ?>
					<tr>
						<td><?php echo $user->ID; ?></td>
						<td><?php echo $user->display_name; ?></td>
						<td><?php echo $user->user_login; ?></td>
					</tr>
				<?php } ?>
			</table>
			<?php
		} else {
			?>
			<div>No users found.</div>
			<a href="<?php echo get_permalink().'?add-user'; ?>"><button>ADD NEW USER</button></a>
			<?php
		}
	}
}