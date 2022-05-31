
<!-- Offline assets -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/table.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/icon.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/modal.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/button.min.css">


<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="http://semantic-ui.com/javascript/library/tablesort.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/modal.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js"></script>



<!-- Actual Code starts -->
<?php
global $wpdb;
$args = array(
	'echo'           => true,
	'remember'       => true,
	'redirect'       => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
	'form_id'        => 'loginform',
	'id_username'    => 'user_login',
	'id_password'    => 'user_pass',
	'id_remember'    => 'rememberme',
	'id_submit'      => 'wp-submit',
	'label_username' => __( 'Username or Email Address' ),
	'label_password' => __( 'Password' ),
	'label_remember' => __( 'Remember Me' ),
	'label_log_in'   => __( 'Log In' ),
	'value_username' => '',
	'value_remember' => false
);
$is_user_logged_in = is_user_logged_in();
if(!$is_user_logged_in){
?>
<h3 id="login_error"></h3>
<table class="ui green table">
    <tr>
        <td><center>
<h3 style="color:blue">Login</h3>
<form method="POST" action="">
    <table class="ui collapsing red striped table">
        <tr>
            <th>Phone Number:</th>
            <td><input type="text" name="phone"></td>
        </tr>
        <tr>
            <th>Password:</th>
            <td><input type="password" name="pwd"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="login" value="Login" class="ui red button"></td>
        </tr>
    </table>
</form>
</center>
</td>
<td><center>
<h3 style="color:red">Register</h3>
<form method="POST" action="">
    <table class="ui collapsing red striped table">
        <tr>
            <th>Phone Number:</th>
            <td><input type="text" name="phone"></td>
        </tr>
        <tr>
            <th>Password:</th>
            <td><input type="password" name="pwd"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="register" value="Register" class="ui red button"></td>
        </tr>
    </table>
</form>
</center></td>
</tr>
</table>
<?php
echo $_POST["pwd"];
//echo do_shortcode("[rp_register_widget]");
} else {
    
    
$user_id = get_current_user_id();
if(isset($_POST["submit"])){
	// The nonce was valid and the user has the capabilities, it is safe to continue.

	// These files need to be included as dependencies when on the front end.
	require_once( ABSPATH . 'wp-admin/includes/image.php' );
	require_once( ABSPATH . 'wp-admin/includes/file.php' );
	require_once( ABSPATH . 'wp-admin/includes/media.php' );
	
	// Let WordPress handle the upload.
	// Remember, 'my_image_upload' is the name of our file input in our form above.
	$full_photo = media_handle_upload( 'full_photo', $_POST['post_id'] );
	
	if ( is_wp_error( $full_photo ) ) {
		//echo '// There was an error uploading the image.';
	} else {
		echo '// The image was uploaded successfully!';
		update_user_meta( $user_id, 'full_photo', $full_photo );
	}
	
	$half_photo = media_handle_upload( 'half_photo', $_POST['post_id'] );
	
	if ( is_wp_error( $half_photo ) ) {
		//echo '// There was an error uploading the image.';
	} else {
		echo '// The image was uploaded successfully!';
		update_user_meta( $user_id, 'half_photo', $half_photo );
	}
	
	$photo = media_handle_upload( 'photo', $_POST['post_id'] );
	
	if ( is_wp_error( $photo ) ) {
		//echo '// There was an error uploading the image.';
	} else {
		echo '// The image was uploaded successfully!';
		update_user_meta( $user_id, 'photo', $photo );
	}

    update_user_meta( $user_id, 'education', $_POST['education'] );
    update_user_meta( $user_id, 'date_of_birth', $_POST['date_of_birth'] );
    update_user_meta( $user_id, 'gender', $_POST['gender'] );
    update_user_meta( $user_id, 'phone', $_POST['phone'] );
    update_user_meta( $user_id, 'status', $_POST['status'] );
    update_user_meta( $user_id, 'father', $_POST['father'] );
    update_user_meta( $user_id, 'address', $_POST['address'] );
  update_user_meta( $user_id, 'job', $_POST['job'] );
  update_user_meta( $user_id, 'salary', $_POST['salary'] );
    update_user_meta( $user_id, 'town', $_POST['town'] );
    update_user_meta( $user_id, 'religion', $_POST['religion'] );
    update_user_meta( $user_id, 'caste', $_POST['caste'] );
    update_user_meta( $user_id, 'nakshatram', $_POST['nakshatram'] );

    $display_name = $_POST['display_name'];

    $wpdb->update( $wpdb->users , 
        array( 
    		'display_name' => $display_name
    	), 
    	array( 'ID' => $user_id )
    );
    
}
$pre_display = $wpdb->get_var( $wpdb->prepare("SELECT display_name FROM $wpdb->users WHERE ID = %d",$user_id));
$user_meta = get_user_meta($user_id);
//premuim_mem_message();
?>


<h4>Click here to <a href="<?php echo wp_logout_url(get_permalink()); ?>">Logout</a></h4>
<form id="featured_upload" method="post" action="" enctype="multipart/form-data">
    <table class="ui striped collapsing table">
    <tr>
        <th><label for="display_name">Display Name: </label></th>
        <td>
            <input type="text" name="display_name" id="display_name" value="<?php echo $pre_display; ?>" /><br />
            
        </td>
    </tr>
    <tr>
        <th><label for="education">Education:</label></th>
        <td>
            <input type="text" name="education" id="education" value="<?php echo esc_attr( get_user_meta( $user_id ,'education', true ) ); ?>" /><br />
            
        </td>
    </tr>
    <tr>
        <th><label for="date_of_birth">Date of birth:</label></th>
        <td>
            <input type="text" name="date_of_birth" id="date_of_birth" value="<?php echo esc_attr( get_user_meta( $user_id,'date_of_birth', ture ) ); ?>" /><br />
        </td>
    </tr>
    <tr>
        <th><label for="height">Height:</label></th>
        <td>
            <input type="date" name="height" id="height" value="<?php echo esc_attr( get_user_meta( $user_id,'height', ture ) ); ?>" required=""/><br />
        </td>
    </tr>
    <tr>
        <th><label for="gender"><?php _e("Gender:"); ?></label></th>
        <td>
	        <select name="gender" id="gender" >
	        	<option value="Male" <?php if('Male'==esc_attr( get_user_meta($user_id, 'gender', true ) ) ){
	        		echo 'selected="selected"'; } ?>>Male</option>
	        	<option value="Female" <?php if('Female'==esc_attr( get_user_meta($user_id, 'gender', true ) ) ){
	        		echo 'selected="selected"'; } ?>>Female</option>
	        </select>
	        <br />
	    </td>
    </tr>
    <tr>
        <th><label for="status"><?php _e("Status"); ?></label></th>
        <td>
	        <select name="status" id="status" >
	        	<option value="Single" 
	        	    <?php if('Single'==esc_attr( get_user_meta($user_id, 'status', true ) ) ){
	        		echo 'selected="selected"'; } ?>>Single</option>
	        	<option value="Married" 
	        	    <?php if('Married'==esc_attr( get_user_meta($user_id, 'status', true ) ) ){
	        		echo 'selected="selected"'; } ?>>Married</option>
	        	<option value="Divorced" 
	        	    <?php if('Divorced'==esc_attr( get_user_meta($user_id, 'status', true ) ) ){
	        		echo 'selected="selected"'; } ?>>Divorced</option>
	        </select>
	        <br />
	    </td>
    </tr>
    <tr>
        <th><label for="phone"><?php _e("Phone:"); ?></label></th>
        <td>
            <input type="text" name="phone" id="phone" value="<?php echo esc_attr( get_user_meta( $user_id ,'phone', true ) ); ?>" /><br />
            
        </td>
    </tr>
    <tr>
        <th><label for="father"><?php _e("Father Name:"); ?></label></th>
        <td>
            <input type="text" name="father" id="father" value="<?php echo esc_attr( get_user_meta( $user_id ,'father', true ) ); ?>" /><br />
            
        </td>
    </tr>
    <tr>
        <th><label for="address"><?php _e("Address:"); ?></label></th>
        <td>
            <textarea name="address" id="address"><?php echo esc_attr( get_user_meta( $user_id ,'address', true ) ); ?></textarea><br />
            
        </td>
    </tr>
    <tr>
        <th><label for="religion">Caste:</label></th>
        <td>
            <select name="religion" id="religion" class="ui dropdown">
                <option value="Jangam">Jangam</option>
                <option value="Lingayath">Lingayath</option>
                <oprion value="Gandla">Gandla</oprion>
                <option value="Gowli">Gowli</option>
                <option value="Chippe">Chippe</option>
            </select>
            <br />
            <script>
                document.getElementById('religion').value="<?php echo $user_meta['religion'][0]; ?>";
            </script>
        </td>
    </tr>
    <tr>
        <th><label for="caste">Sub Caste:</label></th>
        <td>
            <input type="text" name="caste" value="<?php echo $user_meta['caste'][0]; ?>">
        </td>
    </tr>
    <tr>
        <th><label for="nakshatram">Nakshatram:</label></th>
        <td>
            <input type="text" name="nakshatram" value="<?php echo $user_meta['nakshatram'][0]; ?>">
        </td>
    </tr>
    <tr>
        <th><label for="town">Town</label></th>
        <td>
            <input type="text" name="town" 
                value="<?php echo $user_meta['town'][0]; ?>">
        </td>
    </tr>
      <tr>
        <th><label for="job">Job</label></th>
        <td>
            <input type="text" name="job" 
                value="<?php echo $user_meta['job'][0]; ?>" required="">
        </td>
    </tr>
      <tr>
        <th><label for="salary">Salary</label></th>
        <td>
            <input type="text" name="salary" required=""
                value="<?php echo $user_meta['salary'][0]; ?>">
        </td>
    </tr>
    <tr>
        <th><label for="photo"><?php _e("Full Photo"); ?></label></th>
        <td><?php
            $photo_id = get_user_meta( $user_id ,'full_photo', true ) ;
            
            $photo_src = wp_get_attachment_image_src( $photo_id, 'thumbnail', 'false' );
            echo '<img src="'.$photo_src[0].'">';
            //echo do_shortcode('[gallery size="large" ids="'.$photo_id.'"]');
            ?>
            <center>
        	    Change: <input type="file" name="full_photo" id="full_photo"  multiple="false" />
        	</center>
        	<input type="hidden" name="post_id" id="post_id" value="0" />
        	<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
        </td>
    </tr>
    <tr>
        <th><label for="photo"><?php _e("Half Photo"); ?></label></th>
        <td><?php
            $photo_id = get_user_meta( $user_id ,'half_photo', true ) ;
            
            $photo_src = wp_get_attachment_image_src( $photo_id, 'thumbnail', 'false' );
            echo '<img src="'.$photo_src[0].'">';
            //echo do_shortcode('[gallery ids="'.$photo_id.'"]');
            ?>
            <center>
        	    Change: <input type="file" name="half_photo" id="half_photo"  multiple="false" />
        	</center>
        	
        	<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
        </td>
    </tr>
    <tr>
        <th><label for="photo"><?php _e("Closeup Photo"); ?></label></th>
        <td><?php
            $photo_id = get_user_meta( $user_id ,'photo', true ) ;
            $photo_src = wp_get_attachment_image_src( $photo_id, 'thumbnail', 'false' );
            echo '<img src="'.$photo_src[0].'">';
            ?>
            <center>
        	    Change: <input type="file" name="photo" id="photo"  multiple="false" />
        	</center>
        	
        	<?php wp_nonce_field( 'my_image_upload', 'my_image_upload_nonce' ); ?>
        </td>
    </tr>
</table>
<script type="text/javascript">
    $('input').attr('required','');
    $('input[type=file]').removeAttr('required');
    $('textarea').attr('required','');
</script>

<br>
<input id="submit" name="submit" type="submit" value="Save changes" class="ui blue button"/></form>


<?php
}