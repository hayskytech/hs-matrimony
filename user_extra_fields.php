<?php
add_action( 'personal_options_update', 'save_extra_user_profile_fields_oya' );
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields_oya' );

function save_extra_user_profile_fields_oya( $user_id ) {
    if(!current_user_can( 'edit_user', $user_id ) ) { 
        return false; 
    }
    update_user_meta($user_id, 'phone', $_POST["phone"]);
    update_user_meta($user_id, 'password', $_POST["password"]);
    update_user_meta($user_id, 'education', $_POST["education"]);
    update_user_meta($user_id, 'date_of_birth', $_POST["date_of_birth"]);
    update_user_meta($user_id, 'gender', $_POST["gender"]);
    // update_user_meta($user_id, 'status', $_POST["status"]);
    update_user_meta($user_id, 'father_name', $_POST["father_name"]);
    update_user_meta($user_id, 'address', $_POST["address"]);
    update_user_meta($user_id, 'religion', $_POST["religion"]);
    // update_user_meta($user_id, 'sub_caste', $_POST["sub_caste"]);
    update_user_meta($user_id, 'nakshatram', $_POST["nakshatram"]);
    update_user_meta($user_id, 'town', $_POST["town"]);
    update_user_meta($user_id, 'job', $_POST["job"]);
    update_user_meta($user_id, 'salary', $_POST["salary"]);
    update_user_meta($user_id, 'height', $_POST["height"]);
    update_user_meta($user_id, 'full_photo', $_POST["full_photo"]);
    update_user_meta($user_id, 'half_photo', $_POST["half_photo"]);
    update_user_meta($user_id, 'photo', $_POST["photo"]);
}

add_action( 'show_user_profile', 'extra_user_profile_fields_oya' );
add_action( 'edit_user_profile', 'extra_user_profile_fields_oya' );

function extra_user_profile_fields_oya( $user ) { 
    $user_id = $user->ID;
    ?>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.0.js"></script>
    <h3 id="matrimony_info">Extra profile information</h3>
    <table class="form-table">
        <tr>
            <td>Phone</td>
            <td><input type="text" name="phone"></td>
        </tr>
        <tr>
            <td>Password</td>
            <td><input type="text" name="password"></td>
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
        <td>Gender</td>
        <td><select class="ui dropdown" name="gender">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            </select>
        </td>
        </tr>
        <!-- <tr>
        <td>Status</td>
        <td><select class="ui dropdown" name="status">
            <option value="Single">Single</option>
            <option value="Married">Married</option>
            <option value="Divorced">Divorced</option>
            </select>
        </td>
        </tr> -->
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
            <td><input type="text" name="job"></td>
        </tr>
        <tr>
            <td>Salary</td>
            <td><input type="text" name="salary"></td>
        </tr>
        <tr>
            <td>Height</td>
            <td><input type="text" name="height"></td>
        </tr>
        <tr>
        <td>Full Photo</td>
        <td>
            <div class="image-preview-wrapper">
                <img id="img_full_photo" src="" height="100">
            </div>
            <input id="upload_image_button" type="button" class="ui blue button" value="Choose Media" 
                onclick="choose_media('full_photo','img_full_photo')"/>
            <input type="hidden" name="full_photo" id="full_photo">
        </td>
        <?php
        wp_enqueue_media();
        add_action( 'admin_footer', 'media_selector_print_scripts' );
        add_action( 'wp_footer', 'media_selector_print_scripts' );
        function media_selector_print_scripts() {
            ?>
            <script type='text/javascript'>
            function choose_media(image_input_id, image_preview_id='', saved_img_id='') {
                var file_frame;
                var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
                var set_to_post_id = saved_img_id; // Set this
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
                    if (image_preview_id) {
                        $( '#'+image_preview_id ).attr( 'src', attachment.url ).css( 'width', 'auto' );
                    }
                    $( '#'+image_input_id ).val( attachment.id );
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
        <tr>
        <td>Half Photo</td>
        <td>
            <div class="image-preview-wrapper">
                <img id="img_half_photo" src="" height="100">
            </div>
            <input id="upload_image_button" type="button" class="ui blue button" value="Choose Media" 
                onclick="choose_media('half_photo','img_half_photo')"/>
            <input type="hidden" name="half_photo" id="half_photo">
        </td>
        </tr>
        <tr>
        <td>Photo</td>
        <td>
            <div class="image-preview-wrapper">
                <img id="img_photo" src="" height="100">
            </div>
            <input id="upload_image_button" type="button" class="ui blue button" value="Choose Media" 
                onclick="choose_media('photo','img_photo')"/>
            <input type="hidden" name="photo" id="photo">
        </td>
        </tr>
    </table>
    <script type="text/javascript">
        $('input').addClass('regular-text');
        $('input[name=phone]').val('<?php echo get_the_author_meta('phone', $user->ID); ?>');
        $('input[name=password]').val('<?php echo get_the_author_meta('password', $user->ID); ?>');
        $('input[name=education]').val('<?php echo get_the_author_meta('education', $user->ID); ?>');
        $('input[name=date_of_birth]').val('<?php echo get_the_author_meta('date_of_birth', $user->ID); ?>');
        $('select[name=gender]').val('<?php echo get_the_author_meta('gender', $user->ID); ?>');
        // $('select[name=status]').val('<?php echo get_the_author_meta('status', $user->ID); ?>');
        $('input[name=father_name]').val('<?php echo get_the_author_meta('father_name', $user->ID); ?>');
        $('input[name=address]').val('<?php echo get_the_author_meta('address', $user->ID); ?>');
        $('select[name=religion]').val('<?php echo get_the_author_meta('religion', $user->ID); ?>');
        // $('input[name=sub_caste]').val('<?php echo get_the_author_meta('sub_caste', $user->ID); ?>');
        $('input[name=nakshatram]').val('<?php echo get_the_author_meta('nakshatram', $user->ID); ?>');
        $('input[name=town]').val('<?php echo get_the_author_meta('town', $user->ID); ?>');
        $('input[name=job]').val('<?php echo get_the_author_meta('job', $user->ID); ?>');
        $('input[name=salary]').val('<?php echo get_the_author_meta('salary', $user->ID); ?>');
        $('input[name=height]').val('<?php echo get_the_author_meta('height', $user->ID); ?>');
        <?php 
        $img_id = get_the_author_meta('full_photo', $user->ID);
        $img_url = wp_get_attachment_image_src($img_id, 'medium');
        ?>
        $('#img_full_photo').attr('src','<?php echo $img_url[0]; ?>');
        $('#full_photo').val('<?php echo $img_id; ?>');
        <?php 
        $img_id = get_the_author_meta('half_photo', $user->ID);
        $img_url = wp_get_attachment_image_src($img_id, 'medium');
        ?>
        $('#img_half_photo').attr('src','<?php echo $img_url[0]; ?>');
        $('#half_photo').val('<?php echo $img_id; ?>');
        <?php 
        $img_id = get_the_author_meta('photo', $user->ID);
        $img_url = wp_get_attachment_image_src($img_id, 'medium');
        ?>
        $('#img_photo').attr('src','<?php echo $img_url[0]; ?>');
        $('#photo').val('<?php echo $img_id; ?>');
        // Hide some default options //
            
            $('.user-url-wrap').hide();
            $('.user-description-wrap').hide();
            $('.user-profile-picture').hide();
            $('.user-rich-editing-wrap').hide();
            $('.user-admin-color-wrap').hide();
            $('.user-comment-shortcuts-wrap').hide();
            $('.show-admin-bar').hide();
            $('.user-language-wrap').hide();
            //*/
    </script>
<?php 
}

function new_modify_user_table_oya( $column ) {
    $column['user_id'] = 'ID';
    $column['display_name'] = 'Names';
	$column['phone'] = 'Phone';
    $column['password'] = 'Password';
    $column['education'] = 'Education';
    $column['date_of_birth'] = 'Date Of Birth';
    $column['gender'] = 'Gender';
    // $column['status'] = 'Status';
    $column['father_name'] = 'Father Name';
    $column['address'] = 'Address';
    $column['religion'] = 'Religion';
    // $column['sub_caste'] = 'Sub Caste';
    $column['nakshatram'] = 'Nakshatram';
    $column['town'] = 'Town';
    $column['job'] = 'Job';
    $column['salary'] = 'Salary';
    $column['full_photo'] = 'Full Photo';
    $column['half_photo'] = 'Half Photo';
    $column['photo'] = 'Photo';
    return $column;
}
add_filter( 'manage_users_columns', 'new_modify_user_table_oya' );

function new_modify_user_table_row_oya( $val, $column_name, $user_id ) {
    $meta = get_user_meta($user_id);
	$data = get_userdata($user_id);
    switch ($column_name) {
        case 'display_name' :
            return $data->display_name;
		case 'user_id' :
            return $user_id;
		case 'phone' :
            $phone = $meta['phone'][0];
            return $phone;
        case 'password' :
            $password = $meta['password'][0];
            return $password;
        case 'education' :
            $education = $meta['education'][0];
            return $education;
        case 'date_of_birth' :
            $date_of_birth = $meta['date_of_birth'][0];
            return $date_of_birth;
        case 'gender' :
            $gender = $meta['gender'][0];
            return $gender;
        // case 'status' :
        //     $status = $meta['status'][0];
        //     return $status;
        case 'father_name' :
            $father_name = $meta['father_name'][0];
            return $father_name;
        case 'address' :
            $address = $meta['address'][0];
            return $address;
        case 'religion' :
            $religion = $meta['religion'][0];
            return $religion;
        // case 'sub_caste' :
        //     $sub_caste = $meta['sub_caste'][0];
        //     return $sub_caste;
        case 'nakshatram' :
            $nakshatram = $meta['nakshatram'][0];
            return $nakshatram;
        case 'town' :
            $town = $meta['town'][0];
            return $town;
        case 'job' :
            $job = $meta['job'][0];
            return $job;
        case 'salary' :
            $salary = $meta['salary'][0];
            return $salary;
        case 'full_photo' :
            $full_photo = $meta['full_photo'][0];
            return $full_photo;
        case 'half_photo' :
            $half_photo = $meta['half_photo'][0];
            return $half_photo;
        case 'photo' :
            $photo = wp_get_attachment_image_src($meta['photo'][0])[0];
            return '<img src="'.$photo.'">';
        default:
    }
    return $val;
}
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row_oya', 10, 3 );