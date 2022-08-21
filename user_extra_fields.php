<?php
/* -- User can edit his own profile from dashboard with these lines. -- */
// add_action( 'personal_options_update', 'save_extra_user_profile_fields_yno' );
// add_action( 'show_user_profile', 'extra_user_profile_fields_yno' );

/* -- ADMIN can edit all user profiles from dashboard with these lines. -- */
add_action( 'edit_user_profile_update', 'save_extra_user_profile_fields_yno' );
add_action( 'edit_user_profile', 'extra_user_profile_fields_yno' );

/* -- Actual Code starts here -- */
function save_extra_user_profile_fields_yno( $user_id ) {
  if(!current_user_can( 'edit_user', $user_id ) ) { 
    return false; 
  }
  update_user_meta($user_id, 'gender', $_POST["gender"]);
  update_user_meta($user_id, 'agent', $_POST["agent"]);
}


function extra_user_profile_fields_yno( $user ) { 
  $user_id = $user->ID;
  $args = array(
    'post_type'   => 'matrimony_field',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'orderby' => 'date',
    'order' => 'ASC',
  );
  $posts = get_posts( $args );
  echo '<pre>';
  // print_r($posts);
  echo '</pre>';
  ?>
  <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.0.min.js"></script>
  <h3 id="matrimony_profile">Matrimony information</h3>
  <table class="form-table">
    <tr>
    <td>Gender</td>
    <td><select  class="ui search dropdown"  name="gender" >
        <option value="">Select</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
      </select>
    </td>
    </tr>
    <?php
    foreach ($posts as $post) {
      ?>
      <tr>
        <td><?php echo $post->post_title; ?></td>
        <td><input type="text" name="<?php echo $post->post_name; ?>"></td>
      </tr>
      <?php
    }
    ?>
    <tr>
      <td>Agent</td>
      <td>
        <?php
        $args = array(
          'role__in' => array('agent')
        );
        $agents = get_users($args);
        ?>
        <select name="agent">
          <option></option>
          <?php
          foreach ($agents as $agent) {
            echo '<option>'.$agent->display_name.'</option>';
          }
          ?>
        </select>
      </td>
    </tr>
  </table>
  <script type="text/javascript">
    $('.user-url-wrap').hide();
    $('.user-description-wrap').hide();
    $('.user-profile-picture').hide();
    $('.user-rich-editing-wrap').hide();
    $('.user-admin-color-wrap').hide();
    $('.user-comment-shortcuts-wrap').hide();
    $('.show-admin-bar').hide();
    $('.user-language-wrap').hide();
    $('.user-generate-reset-link-wrap').hide();
    $('#application-passwords-section').hide();
    $('input').addClass('regular-text');
    $('select[name=gender]').val('<?php echo get_the_author_meta('gender', $user->ID); ?>');
    $('select[name=agent]').val('<?php echo get_the_author_meta('agent', $user->ID); ?>');
    $(".ui.dropdown").dropdown();
  </script>
<?php 
}

/* -- Add extra columns to "Users Lists" in Admin Dashboard -- */
add_filter( 'manage_users_columns', 'new_modify_user_table_yno' );
add_filter( 'manage_users_custom_column', 'new_modify_user_table_row_yno', 10, 3 );

function new_modify_user_table_yno( $column ) {
  $column['gender'] = 'Gender';
  $column['agent'] = 'Agent';
  return $column;
}
function new_modify_user_table_row_yno( $val, $column_name, $user_id ) {
  $meta = get_user_meta($user_id);
  switch ($column_name) {
    case 'gender' :
      return $meta['gender'][0];
    case 'agent' :
      return get_userdata( $meta['agent'][0] )->display_name;
    default:
  }
  return $val;
}