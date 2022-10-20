<style type="text/css">
	.menu-toggle, button{
		color: black;
	}
</style>
<?php
global $wpdb;
if (isset($_GET['add-user'])) {
	$add_user = true;
}
if (isset($_GET["id"]) || $add_user) {
	$no_user = '<h3>User does not exist.</h3>';
	$user_id = $_GET["id"];
	if (!get_user_by( 'ID', $user_id ) && !$add_user) {
		echo $no_user;
		return;
	}
	if (current_user_can('administrator')){
		$admin = true;
	}
	if (current_user_can( 'agent' )) {
		$agent = get_usermeta( $user_id, $meta_key = 'agent', true );
		if (get_current_user_id()==$agent) {
			$agent = true;
		}
	}
	if ($user_id == get_current_user_id()) {
		$agent = true;
	}
	if (user_can( $user_id, $capability='agent' )) {
		if (current_user_can( 'administrator' )) {
			$admin_edit_agent = true;
		} else {
			echo $no_user;
			return;
		}
	}
} else if (isset($_GET['my-profile'])) {
	$user_id = get_current_user_id();
	$agent = true;
}
if ($add_user){
	if (isset($_POST['add_user'])) {
		$id = wp_insert_user( array(
			'user_login' => $_POST['phone'],
			'user_pass' => $_POST['password'],
			'display_name' => '',
			'role' => $_POST['role'],
		) );
		if (! is_wp_error($id)) {
			update_user_meta( $id, 'gender', $_POST['gender']);
			if (current_user_can('agent')) {
				update_user_meta( $id, 'agent', get_current_user_id());
			}
			echo '<h3 style="color:green">User Added successfully. 
			<br>Go Back to <a href="'.get_permalink().'">All Profiles</a>
			<br><a href="'.get_permalink().'?id='.$id.'">View Profile</a></h3>';
		} else {
			foreach ($id->errors as $value) {
				echo '<h3 style="color:red">'.$value[0].'</h3>';
			}
		}
	}
	?>
	<h3>Add User</h3>
	<form method="post">
		<table>
			<tr>
				<td>Phone</td>
				<td><input type="text" name="phone"></td>
			</tr>
			<tr>
				<td>Password</td>
				<td><input type="text" name="password"></td>
			</tr>
			<tr>
				<td>Gender</td>
				<td>
					<select name="gender">
						<option>Male</option>
						<option>Female</option>
					</select>
				</td>
			</tr>
			<?php
			if ($admin) {
				?>
				<tr>
					<td>Role</td>
					<td>
						<select name="role">
							<option value="subscriber">User</option>
							<option value="agent">Agent</option>
						</select>
					</td>
				</tr>
				<?php
			}
			?>
			<tr>
				<td></td>
				<td><input type="submit" name="add_user"></td>
			</tr>
		</table>
	</form>
	<script type="text/javascript">
	if ( window.history.replaceState ) {
		window.history.replaceState( null, null, window.location.href );
	}
	</script>
	<?php
}
if ($user_id ) {
	$args = array(
		'post_type'	  => 'matrimony_field',
		'post_status'	=> 'publish',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'ASC',
	);
	if (!$admin && !$agent) {
	  $args['meta_query'][0] = array(
	    'key' => 'public_visibility',
	    'value'   => array('yes'),

	  );
	}
	$posts = get_posts( $args );
	/*-- Save the details of current user --*/
	if(isset($_POST["submit"])){
		$data = array( 'ID' => $user_id);
		$data["display_name"] = $_POST["display_name"];
		update_user_meta($user_id, 'date_of_birth', addslashes($_POST["date_of_birth"]));
		update_user_meta($user_id, 'community', $_POST["community"]);
		foreach ($posts as $post) {
			// global $post;
			update_user_meta($user_id, $post->post_name, addslashes($_POST[$post->post_name]));
		}
		$result = wp_update_user($data);
	}
	$data = get_userdata($user_id);
	$meta = get_user_meta($user_id);
	$logout_redirect = get_permalink();
	if ($admin_edit_agent) {
		echo '<h3>Agent</h3>';
	}
	if (!$admin_edit_agent) {
		echo '<h3>View Profile</h3>';
	}
	$display_name = get_userdata( get_current_user_id() )->display_name;
	echo '<big><a href="'.wp_logout_url( $logout_redirect ).'"><b>Logout</b></a></big>';
	?>
	<form method="post" enctype="multipart/form-data" class="ui form matrimony">
		<table class="ui collapsing unstackable striped table">
			<tr>
				<td>Name</td>
				<td><input type="text" name="display_name"></td>
			</tr>
			<tr>
				<td>Date Of Birth</td>
				<td><input type="date" name="date_of_birth"></td>
			</tr>
			<tr>
				<td>Community</td>
				<td>
					<select name="community">
						<option></option>
						<?php
						$args = array(
					    'taxonomy'    => 'community',
					    'orderby'     => 'term_id',
					    'order'       => 'ASC',
					    // 'parent'      => 0,
					    'hide_empty'  => false,
						);
						$terms = get_terms($args);
						foreach ($terms as $term) {
							echo '<option>'.$term->name.'</option>';
						}
						?>
					</select>
				</td>
			</tr>
			<?php
			foreach ($posts as $post) {
				$type = get_post_meta( $post->ID,'matrimony_field_type',true);
				echo '<tr><td>'.$post->post_title.'</td><td>';
				if ($type=='select') {
					$terms = get_the_terms( $post->ID,'matrimony_option');
					$options = array();
					foreach($terms as $term){
						$options[$term->term_id] = $term->name;
					}
					ksort($options);
					echo '<select name="'.$post->post_name.'" class="ui dropdown">';
					foreach($options as $option){
						echo '<option>'.$option.'</option>';
					}
					echo '</select>';
				} else if ($type=='image') {
					?>
					<div class="image-preview-wrapper">
						<img src="" style="max-width:250px" id="img_<?php echo $post->post_name; ?>">
					</div>
					<input type="button" class="ui blue button image" value="Choose Media" onclick="choose_media(this)" />
					<input type="hidden" name="<?php echo $post->post_name; ?>">
					<?php
				} else {
					echo '<input type="text" name="'.$post->post_name.'">';
				}
				echo '</td></tr>';
			}
			?>
			<tr>
				<td></td>
				<td><input type="submit" name="submit" class="ui green button save" value="Save"></td>
			</tr>
		</table>
	</form>
	<script type="text/javascript">
	if ( window.history.replaceState ) {
		window.history.replaceState( null, null, window.location.href );
	}
	</script>
	<script type="text/javascript">
		$('input[name=display_name]').val('<?php echo $data->display_name; ?>');
		$('input[name=date_of_birth]').val('<?php echo $meta["date_of_birth"][0]; ?>');
		$('select[name=community]').val('<?php echo $meta["community"][0]; ?>');
		<?php 
		foreach ($posts as $post) {
			$type = get_post_meta( $post->ID,'matrimony_field_type',true);
			if ($type == 'select') {
				echo '$("select[name='.$post->post_name.']").val("'.$meta[$post->post_name][0].'");
			';
			} else if ($type == 'image') {
				$img_id = $meta[$post->post_name][0];
				$img_url = wp_get_attachment_image_src($img_id, 'medium');
				?>
				$('#img_<?php echo $post->post_name; ?>').attr('src','<?php echo $img_url[0]; ?>');
				$('input[name=<?php echo $post->post_name; ?>]').val('<?php echo $img_id; ?>');
				<?php
			} else {
				echo '$("input[name='.$post->post_name.']").val("'.$meta[$post->post_name][0].'");
			';
			}
		}
		?>
	</script>
	<script type="text/javascript">
		<?php
		if (!$admin && !$agent && ($user_id!=get_current_user_id()) && !$add_user) {
			?>
			$('.matrimony input').attr('readonly','');
			$('.matrimony select').attr('disabled','disabled');
			$('.matrimony .image.button').attr('disabled','disabled');
			$('.matrimony .image.button').hide();
			$('.matrimony .save.button').hide();
			<?php
		} else {
			?>
			$('.ui.dropdown').dropdown();
			<?php
		}
		?>
	</script>
	<?php
	wp_enqueue_media();
	add_action( 'admin_footer', 'media_selector_print_scripts' );
	add_action( 'wp_footer', 'media_selector_print_scripts' );
	if (!function_exists('media_selector_print_scripts')) {
		function media_selector_print_scripts() {
			?>
			<script type='text/javascript'>
			function choose_media(x) {
				var file_frame;
				var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
				var set_to_post_id = $(x).parent().find('input[type=hidden]').val(); // Set this
				if ( file_frame ) {
					file_frame.uploader.uploader.param( 'post_id', set_to_post_id );
					file_frame.open();
					return;
				} else {
					wp.media.model.settings.post.id = set_to_post_id;
				}
				file_frame = wp.media.frames.file_frame = wp.media({
					title: 'Select a image to upload',
					button: {
						text: 'Use this image',
					},
					multiple: false
				});
				// When an image is selected, run a callback.
				file_frame.on( 'select', function() {
					// We set multiple to false so only get one image from the uploader
					attachment = file_frame.state().get('selection').first().toJSON();
					// Do something with attachment.id and/or attachment.url here
					$(x).parent().find('img').attr( 'src', attachment.url ).css( 'width', 'auto' );
					$(x).parent().find('input[type=hidden]').val( attachment.id );
					// Restore the main post ID
					wp.media.model.settings.post.id = wp_media_post_id;
				});
					// Finally, open the modal
					file_frame.open();
				// Restore the main ID when the add media button is pressed
				jQuery( 'a.add_media' ).on( 'click', function() {
					wp.media.model.settings.post.id = wp_media_post_id;
				});
			}
			</script>
			<?php
		}
	}
}