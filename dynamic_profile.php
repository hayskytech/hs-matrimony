<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js"></script>
<style type="text/css">
	.menu-toggle, button{
		color: black;
	}
</style>
<?php
global $wpdb;
$user_id = get_current_user_id();
if ($user_id) {
	$args = array(
		'post_type'	  => 'matrimony_field',
		'post_status'	=> 'publish',
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'ASC',
	);
	$loop = new WP_Query( $args );
	if(isset($_POST["submit"])){
		$data = array( 'ID' => $user_id);
		$data["display_name"] = $_POST["display_name"];
		update_user_meta($user_id, 'date_of_birth', addslashes($_POST["date_of_birth"]));
		while ( $loop->have_posts() ) : $loop->the_post();
			global $post;
			update_user_meta($user_id, $post->post_name, addslashes($_POST[$post->post_name]));
		endwhile;		
		$result = wp_update_user($data);
	}
	$data = get_userdata($user_id);
	$meta = get_user_meta($user_id);
	$logout_redirect = get_permalink();
	echo 'You are already logged in. <a href="'.wp_logout_url( $logout_redirect ).'"><b>Logout</b></a>';
	?>
<form method="post" enctype="multipart/form-data" class="ui form">
	<table class="ui collapsing striped table">
		<tr>
			<td>Name</td>
			<td><input type="text" name="display_name"></td>
		</tr>
		<tr>
			<td>Date Of Birth</td>
			<td><input type="date" name="date_of_birth"></td>
		</tr>
		<?php
		while ( $loop->have_posts() ) : $loop->the_post();
			global $post;
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
				<input type="button" class="ui blue button" value="Choose Media" onclick="choose_media(this)" />
				<input type="hidden" name="<?php echo $post->post_name; ?>">
				<?php
			} else {
				echo '<input type="text" name="'.$post->post_name.'">';
			}
			echo '</td></tr>';
		endwhile;
		?>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" class="ui green button" value="Save"></td>
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
	<?php 
	while ( $loop->have_posts() ) : $loop->the_post();
		global $post;
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
	endwhile;
	?>
$('.ui.dropdown').dropdown();
</script>
<?php
wp_enqueue_media();
add_action( 'admin_footer', 'media_selector_print_scripts' );
add_action( 'wp_footer', 'media_selector_print_scripts' );
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
} else {
	echo do_shortcode('[firebase_otp_login]');
}