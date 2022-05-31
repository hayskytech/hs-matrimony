<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
<?php
global $wpdb;
$user_id = get_current_user_id();
if ($user_id) {
$i = 0;
$pics = get_option('matrimony_photos');
$fields = explode(';
',get_option('matrimony_fields'));
$fields[count($fields)-1] = str_replace(';', '', end($fields));
foreach ($fields as $field) {
    if ($field) {
        if (strpos($field, ',')) {
            $arr[$i] = explode(',', $field);
            $field_name[$i] = $arr[$i][0];
        } else {
            $field_name[$i] = $field;
        }
        $slug[$i] = strtolower(str_replace(' ', '_', $field_name[$i]));
        $i++;
    }
}
if(isset($_POST["submit"])){
    $data = array( 'ID' => $user_id);
    $data["display_name"] = $_POST["display_name"];
    $meta["photo"] = $_POST["photo"];
    $meta["date_of_birth"] = $_POST["date_of_birth"];
    foreach ($meta as $key => $value) {
        update_user_meta($user_id, $key, addslashes($value));
    }
    $i = 0;
    foreach ($fields as $field) {
        if ($field) {
            update_user_meta($user_id, $slug[$i], addslashes($_POST[$slug[$i]]));
            $i++;
        }
    }
    for ($pic=1; $pic <= $pics ; $pic++) { 
        update_user_meta($user_id, 'photo'.$pic, $_POST['photo'.$pic]);
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
            <td>Date Of Birth</td>
            <td><input type="date" name="date_of_birth"></td>
        </tr>
        <?php
        $i = 0;
        foreach ($fields as $field) {
            if ($field) {
                echo '<tr><td>'.$field_name[$i].'</td><td>';
                if ($arr[$i]) {
                    echo '<select name="'.$slug[$i].'">';
                    for ($k = 1; $k < count($arr[$i]); $k++) {
                        echo '<option>'.$arr[$i][$k].'</option>';
                    }
                    echo '</select>';
                } else {
                    echo '<input type="text" name="'.$slug[$i].'">';
                }
                echo '</td></tr>';
                $i++;
            }
        }
        ?>
        <tr>
            <td>Profile Photo</td>
            <td>
                <div class="image-preview-wrapper">
                    <img src="" style="max-width:250px" id="img_photo"><a id="link_photo">View</a>
                </div>
                <input type="button" class="ui blue mini button" value="Choose Media" onclick="choose_media(this)" />
                <input type="hidden" name="photo">
            </td>
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
            ?>
        </tr>
        <?php
        for ($pic = 1; $pic <= $pics; $pic++) {
            ?>
            <tr>
                <td>Other Photo <?php echo $pic; ?></td>
                <td>
                    <div class="image-preview-wrapper">
                        <img src="" style="max-width:250px" id="img_photo<?php echo $pic; ?>">
                    </div>
                    <input type="button" class="ui blue mini button" value="Choose Media" onclick="choose_media(this)" />
                    <input type="hidden" name="photo<?php echo $pic; ?>">
                </td>
            </tr>
            <?php
        }
        ?>
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
    $('input[name=date_of_birth]').val('<?php echo $meta["date_of_birth"][0]; ?>');
    <?php 
    $i = 0;
    foreach ($fields as $field) {
        if ($arr[$i]) {
            echo '$("select[name='.$slug[$i].']").val("'.$meta[$slug[$i]][0].'");
        ';
        } else {
            echo '$("input[name='.$slug[$i].']").val("'.$meta[$slug[$i]][0].'");
        ';
        }
        $i++;
    }
    ?>
</script>
<script type="text/javascript">
    <?php
    for ($pic=0; $pic <= $pics; $pic++) { 
        if ($pic==0) { $pic = ''; }
        $img_id = $meta["photo".$pic][0];
        $img_url = wp_get_attachment_image_src($img_id, 'medium');
        ?>
        $('#img_photo<?php echo $pic; ?>').attr('src','<?php echo $img_url[0]; ?>');
        $('input[name=photo<?php echo $pic; ?>]').val('<?php echo $img_id; ?>');
        <?php
    }
    ?>
</script>
<?php
} else {
    echo do_shortcode('[firebase_otp_login]');
}