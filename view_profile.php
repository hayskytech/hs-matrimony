<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/button.min.css">
<?php
$ID = $_GET["ID"];
global $wpdb;
?>
<form method="GET">
	Enter ID: <input type="text" name="ID" value="<?php echo $_GET["ID"]; ?>">
	<button>View</button>
</form>
<?php
$data = get_userdata($ID);
$meta = get_user_meta($ID);
$args = array(
  'post_type'   => 'matrimony_field',
  'post_status' => 'publish',
  'posts_per_page' => -1,
  'orderby' => 'date',
  'order' => 'ASC',
  'meta_query' => array(
    array(
      'key' => 'public_visibility',
      'value'   => array('yes'),
      'compare' => 'IN'
    )
  )
);
$loop = new WP_Query( $args );
?>
<table>
	<?php
	while ( $loop->have_posts() ) : $loop->the_post();
		global $post;
		$type = get_post_meta( $post->ID, $key = 'matrimony_field_type', $single = true );
		$value = $meta[$post->post_name][0];
		?>
		<tr>
			<td><?php echo $post->post_title; ?></td>
			<td>
				<?php
				if ($type=='image') {
					echo '<img src="'.wp_get_attachment_image_src( $value, $size = 'large', $icon = false )[0].'" style="max-height:300px">';
				} else {
					echo stripslashes($value); 
				}
				?>
			</td>
		</tr>
		<?php
	endwhile;
	?>
</table>