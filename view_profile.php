<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/button.min.css">
<?php
if (strpos($_GET["ID"], 'VSB')) {
	$ID = substr($_GET["ID"],3);
} else {
	$ID = $_GET["ID"];
}
$int = $_GET["like"];
global $wpdb;
?>
<form method="GET">
	Enter ID: <input type="text" name="ID" value="<?php echo $_GET["ID"]; ?>">
	<button>View</button>
</form>
<?php
$user_id = get_current_user_id();
if (current_user_can('hs_matrimony_user') || 1) {
	$user_meta = get_user_meta($ID);
	$our_meta = get_user_meta($user_id);
	if ($user_meta) {
		$pre_int = $our_meta['interested'][0];
		$pre_likers = $user_meta['likers'][0];
		if ($int) {
// Likers
			$pre_likers_arr = (array)json_decode($pre_likers);
			if (!in_array($user_id, $pre_likers_arr) && $int=='yes') {
				array_push($pre_likers_arr, $user_id);
			} elseif ($int=='no') {
				foreach ($pre_likers_arr as $key => $value) {
					if ($value!=$ID) {
						$likers_final_arr[++$i] = $value;
					}
				}
				$pre_likers_arr = $likers_final_arr;
			}
			$final_likers = json_encode($pre_likers_arr);
			update_user_meta($ID,'likers',$final_likers);
// My Likes
			$pre_int_arr = (array)json_decode($pre_int);
			if (!in_array($ID, $pre_int_arr) && $int=='yes') {
				array_push($pre_int_arr, $ID);
			} elseif ($int=='no') {
				foreach ($pre_int_arr as $key => $value) {
					if ($value!=$ID) {
						$final_arr[++$i] = $value;
					}
				}
				$pre_int_arr = $final_arr;
			}
			$final_int = json_encode($pre_int_arr);
			update_user_meta($user_id,'interested',$final_int);
		}
		$user = get_userdata($ID);
		
		$img_url = wp_get_attachment_image_src($user_meta['photo'][0],'medium')[0];
		$href_url = wp_get_attachment_image_src($user_meta['photo'][0],'large')[0];
		
		$h_img_url = wp_get_attachment_image_src($user_meta['half_photo'][0],'medium')[0];
		$h_href_url = wp_get_attachment_image_src($user_meta['half_photo'][0],'large')[0];
		
		$f_img_url = wp_get_attachment_image_src($user_meta['full_photo'][0],'medium')[0];
		$f_href_url = wp_get_attachment_image_src($user_meta['full_photo'][0],'large')[0];
		?>
		</td>
		<?php echo '
		<div style="font-size:120%; line-height: 150%;">
		<b class="user_id">ID: VSB'.$ID.'</b><br>
		'.$user->display_name.'
		<br>Caste: '.$user_meta['religion'][0].'
		<br><i class="handshake outline icon"></i>Job: '.$user_meta['job'][0].'
		<br><i class="rupee sign icon"></i>Salary: '.$user_meta['salary'][0].'
		<br><i class="birthday cake icon"></i>DOB: '.$user_meta['date_of_birth'][0].'
		<br><i class="arrows alternate vertical icon"></i>Height: '.$user_meta['height'][0].'
		<br><i class="address book outline"></i>
		<span style="text-decoration:underline">Father name:</span> '.$user_meta['father_name'][0].'
		<br>Place: '.$user_meta['town'][0].'
		</div><br>';
		?>
		<a href="<?php echo $href_url; ?>" target="_blank"><img src="<?php echo $img_url; ?>"></a><hr>
		<a href="<?php echo $h_href_url; ?>" target="_blank"><img src="<?php echo $h_img_url; ?>"></a><hr>
		<a href="<?php echo $f_href_url; ?>" target="_blank"><img src="<?php echo $f_img_url; ?>"></a>
		<?php
	} else {
   		echo '<tr><td colspan="4"><h2 style="color:red; text-align:center"><br>No user found!!!</h2></td></tr>';
   	}
}
?>