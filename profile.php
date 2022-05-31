<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/button.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/table.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/dropdown.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
<?php
global $wpdb;
$user_id = get_current_user_id();
if ($user_id) {
if(isset($_POST["submit"])){
    $data = array( 'ID' => $user_id);
    $data["display_name"] = $_POST["display_name"];
    update_user_meta($user_id, 'phone', $_POST["phone"]);
    update_user_meta($user_id, 'education', $_POST["education"]);
    update_user_meta($user_id, 'date_of_birth', $_POST["date_of_birth"]);
    update_user_meta($user_id, 'height', addslashes($_POST["height"]));
    update_user_meta($user_id, 'gender', $_POST["gender"]);
    update_user_meta($user_id, 'status', $_POST["status"]);
    update_user_meta($user_id, 'father_name', $_POST["father_name"]);
    update_user_meta($user_id, 'address', $_POST["address"]);
    update_user_meta($user_id, 'religion', $_POST["religion"]);
    // update_user_meta($user_id, 'sub_caste', $_POST["sub_caste"]);
    update_user_meta($user_id, 'nakshatram', $_POST["nakshatram"]);
    update_user_meta($user_id, 'town', $_POST["town"]);
    update_user_meta($user_id, 'job', $_POST["job"]);
    update_user_meta($user_id, 'salary', $_POST["salary"]);
    if (isset($_POST['full_photo_nonce']) && wp_verify_nonce( $_POST["full_photo_nonce"],'full_photo' )) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        $attachment_id = media_handle_upload( 'full_photo', $_POST['full_photo_pid'] );
        if ( is_wp_error( $attachment_id ) ) {
            // There was an error uploading the image.
        } else {
            // The image was uploaded successfully!
            update_user_meta($user_id, 'full_photo', $attachment_id);
        }
    } else {
        // The security check failed, maybe show the user an error.
    }
    if (isset($_POST['half_photo_nonce']) && wp_verify_nonce( $_POST["half_photo_nonce"],'half_photo' )) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        $attachment_id = media_handle_upload( 'half_photo', $_POST['half_photo_pid'] );
        if ( is_wp_error( $attachment_id ) ) {
            // There was an error uploading the image.
        } else {
            // The image was uploaded successfully!
            update_user_meta($user_id, 'half_photo', $attachment_id);
        }
    } else {
        // The security check failed, maybe show the user an error.
    }
    if (isset($_POST['photo_nonce']) && wp_verify_nonce( $_POST["photo_nonce"],'photo' )) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        $attachment_id = media_handle_upload( 'photo', $_POST['photo_pid'] );
        if ( is_wp_error( $attachment_id ) ) {
            // There was an error uploading the image.
        } else {
            // The image was uploaded successfully!
            update_user_meta($user_id, 'photo', $attachment_id);
        }
    } else {
        // The security check failed, maybe show the user an error.
    }
    $result = wp_update_user($data);
    if ( is_wp_error( $result ) ) {
        // There was an error, probably that user doesnt exist.
    } else {
        // Success!
    }
}
$data = get_userdata($user_id);
$meta = get_user_meta($user_id);
$logout_redirect = site_url().'/login/';
echo 'You are already logged in. <a href="'.wp_logout_url( $logout_redirect ).'"><b>Logout</b></a>';
?>
<form method="post" enctype="multipart/form-data">
    <table class="ui collapsing striped table">
        <tr>
            <td>Name</td>
            <td><input type="text" name="display_name"></td>
        </tr>
        <tr>
            <td>Phone</td>
            <td><input type="text" name="phone"></td>
        </tr>
        <tr>
            <td>Education</td>
            <td><input type="text" name="education"></td>
        </tr>
        <tr>
            <td>Date Of Birth</td>
            <td><input type="date" name="date_of_birth"></td>
        </tr>
        <tr>
            <td>Height</td>
            <td><input type="text" name="height" required=""></td>
        </tr>
        <tr>
            <td>Gender</td>
            <td><select class="ui dropdown" name="gender">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Others">Others</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Status</td>
            <td><select class="ui dropdown" name="status">
                    <option value="Single">Single</option>
                    <option value="Married">Married</option>
                    <option value="Divorced">Divorced</option>
                </select>
            </td>
        </tr>
        <tr>
            <td>Father Name</td>
            <td><input type="text" name="father_name"></td>
        </tr>
        <tr>
            <td>Address</td>
            <td><input type="text" name="address"></td>
        </tr>
        <tr>
            <td>Caste</td>
            <td><select class="ui dropdown" name="religion">
                    <option value="Jangam">Jangam</option>
                    <option value="Lingayath">Lingayath</option>
                    <option value="Gowli">Gowli</option>
                    <option value="Chippe">Chippe</option>
                </select>
            </td>
        </tr>
        <!-- <tr>
            <td>Sub Caste</td>
            <td><input type="text" name="sub_caste"></td>
        </tr> -->
        <tr>
            <td>Nakshatram</td>
            <td><input type="text" name="nakshatram"></td>
        </tr>
        <tr>
            <td>Town</td>
            <td><input type="text" name="town"></td>
        </tr>
        <tr>
            <td>Job</td>
            <td><input type="text" name="job" required=""></td>
        </tr>
        <tr>
            <td>Salary</td>
            <td><input type="text" name="salary" required=""></td>
        </tr>
        <tr>
            <td>Full Photo</td>
            <td>
                <img id="img_full_photo" style="max-width: 250px"><br>
                <input type="file" name="full_photo" id="full_photo">
                <input type="hidden" name="full_photo_pid" id="full_photo_pid" value="0" />
                <?php wp_nonce_field( 'full_photo', 'full_photo_nonce' ); ?>
            </td>
            <script type="text/javascript">
                function readURL_14(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#img_full_photo').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#full_photo").change(function() {
                  readURL_14(this);
                });
            </script>
        </tr>
        <tr>
            <td>Half Photo</td>
            <td>
                <img id="img_half_photo" style="max-width: 250px"><br>
                <input type="file" name="half_photo" id="half_photo">
                <input type="hidden" name="half_photo_pid" id="half_photo_pid" value="0" />
                <?php wp_nonce_field( 'half_photo', 'half_photo_nonce' ); ?>
            </td>
            <script type="text/javascript">
                function readURL_15(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#img_half_photo').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#half_photo").change(function() {
                  readURL_15(this);
                });
            </script>
        </tr>
        <tr>
            <td>Photo</td>
            <td>
                <img id="img_photo" style="max-width: 250px"><br>
                <input type="file" name="photo" id="photo">
                <input type="hidden" name="photo_pid" id="photo_pid" value="0" />
                <?php wp_nonce_field( 'photo', 'photo_nonce' ); ?>
            </td>
            <script type="text/javascript">
                function readURL_16(input) {
                  if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                      $('#img_photo').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                  }
                }
                $("#photo").change(function() {
                  readURL_16(this);
                });
            </script>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Save"></td>
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
    $('input[name=phone]').val('<?php echo $meta["phone"][0]; ?>');
    $('input[name=education]').val('<?php echo $meta["education"][0]; ?>');
    $('input[name=date_of_birth]').val('<?php echo $meta["date_of_birth"][0]; ?>');
    $('input[name=height]').val('<?php echo $meta["height"][0]; ?>');
    $('select[name=gender]').val('<?php echo $meta["gender"][0]; ?>');
    $('select[name=status]').val('<?php echo $meta["status"][0]; ?>');
    $('input[name=father_name]').val('<?php echo $meta["father_name"][0]; ?>');
    $('input[name=address]').val('<?php echo $meta["address"][0]; ?>');
    $('select[name=religion]').val('<?php echo $meta["religion"][0]; ?>');
    // $('input[name=sub_caste]').val('<?php echo $meta["sub_caste"][0]; ?>');
    $('input[name=nakshatram]').val('<?php echo $meta["nakshatram"][0]; ?>');
    $('input[name=town]').val('<?php echo $meta["town"][0]; ?>');
    $('input[name=job]').val('<?php echo $meta["job"][0]; ?>');
    $('input[name=salary]').val('<?php echo $meta["salary"][0]; ?>');
    <?php 
    $img_id = $meta["full_photo"][0];
    $img_url = wp_get_attachment_image_src($img_id, 'medium');
    ?>
    $('#img_full_photo').attr('src','<?php echo $img_url[0]; ?>');
    <?php 
    $img_id = $meta["half_photo"][0];
    $img_url = wp_get_attachment_image_src($img_id, 'medium');
    ?>
    $('#img_half_photo').attr('src','<?php echo $img_url[0]; ?>');
    <?php 
    $img_id = $meta["photo"][0];
    $img_url = wp_get_attachment_image_src($img_id, 'medium');
    ?>
    $('#img_photo').attr('src','<?php echo $img_url[0]; ?>');
</script>
<?php
} else {
    echo do_shortcode('[firebase_otp_login]');
}