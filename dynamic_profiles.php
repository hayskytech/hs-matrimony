<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/table.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/icon.min.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js"></script>
<?php
$wpdb;
if (!is_user_logged_in()) {
  ?>
  <h3>Login with Password:</h3>
  <form name="loginform" id="loginform" action="<?php echo site_url(); ?>/wp-login.php" method="post">
  <table>
    <tr>
      <td><label for="user_login">Phone Number / Email Address</label></td>
      <td>
      <input type="text" name="log" autocomplete="username" class="input" value="" size="20">
      </td>
    </tr>
    <tr>
      <td><label for="user_pass">Password</label> </td>
      <td>
        <input type="password" name="pwd" autocomplete="current-password" class="input" value="" size="20">
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
  echo '<h2>Register or Login with OTP:</h2>';
  echo do_shortcode('[df-form]');
  echo '<h2>Register or Login with Gmail:</h2>';
  echo do_shortcode('[nextend_social_login redirect="'.get_permalink().'"]');
  return;
}
if (isset($_GET['search-by-id'])){
  ?>
  <form class="matriform" style="padding:50px 0px">
    <b><big>View by ID:</big></b>
    <br><input type="number" name="id" min="1">
    <br><button>Submit</button>
  </form>
  <?php
  return;
}
if (isset($_GET['id']) || isset($_GET['my-profile']) || isset($_GET['add-user'])) {
  include 'dynamic_profile.php';
  return;
}
$user_id = get_current_user_id();
if (isset($_GET['Gender'])) {
  $this_one = $_GET['Gender'];
} else {
  $gender = get_user_meta($user_id, 'gender', true);
  if($gender == 'Male'){
    $this_one = 'Female';
  } else {
    $this_one = 'Male';
  }
}
if (isset($_GET['number'])) {
  $number = $_GET['number'];
} else {
  $number = -1;
}
$args = array(
  'post_type'   => 'matrimony_field',
  'post_status' => 'publish',
  'posts_per_page' => 1,
  'orderby' => 'date',
  'order' => 'ASC',
  'meta_query' => array(
    array(
      'key' => 'matrimony_field_type',
      'value'   => array('image'),
      'compare' => 'IN'
    ),
  )
);
$profile_pic = get_posts($args)[0]->post_name;
$args['posts_per_page'] = -1;
$args['meta_query'] = array(
  array(
    'key' => 'matrimony_field_type',
    'value'   => array('image'),
    'compare' => 'NOT IN'
  )
);
if (!$admin && !$agent) {
  $args['meta_query'][1] = array(
    'key' => 'public_visibility',
    'value'   => array('yes'),
    'compare' => 'IN'
  );
}
$fields = get_posts($args);
if (!$user_args) {
  $user_args = array(
    'meta_key'     => 'gender',
    'meta_value'   => $this_one,
    'number'     => $number,
    'orderby' => 'rand',
    'role__not_in' => array('administrator','agent')
  );
  if (isset($_GET['agent'])) {
    $user_args['meta_key'] = 'agent';
    $user_args['meta_value'] = get_current_user_id();

  }
}
$blogusers = get_users( $user_args );
if (!$filter_hide) {
  ?>
  <h1>All Profiles</h1>
  <?php echo '<big><a href="'.wp_logout_url( $logout_redirect ).'"><b>Logout</b></a></big>'; ?>
  <div>
    <form class="matriform">
      <b><big>Gender:</big></b>
      <select name="Gender" id="gender-filter">
        <option>Male</option>
        <option>Female</option>
      </select>
      <button>SUBMIT</button>
      <?php
      if (current_user_can('administrator') || current_user_can('agent')){
        echo ' <a href="'.get_permalink().'?add-user"><button type="button">Add User</button></a>';
      } else {
        echo ' <a href="'.get_permalink().'?my-profile"><button type="button">My Profile</button></a>';
      } 
      if (!current_user_can('administrator') && current_user_can('agent')){
        echo ' <a href="'.get_permalink().'?agent='.get_current_user_id().'"><button type="button">My Profiles</button></a>';
      }
      ?>
    </form>
  </div>
  <?php
}
?>
<script type="text/javascript">
  $('select[name=Gender]').val('<?php echo $this_one; ?>')
</script>
<div class="ui four doubling stackable cards">
  <?php
  $my_likes = (array) json_decode(get_user_meta($user_id,'interested',true));
  foreach ($blogusers as $user) {
    $user_meta = get_user_meta($user->ID);
    $img_id = $user_meta[$profile_pic][0];
    if(!$img_id){
      $img_url = 'https://www.gravatar.com/avatar/00000000000000000000000000000000?s=300&d=mp';
    } else {
      $img_url = wp_get_attachment_image_src($img_id,'medium')[0];
    }
    ?>
    <div class="card">
      <a href="<?php echo get_permalink().'?id='.$user->ID; ?>">
        <div style="background-image: url('<?php echo $img_url; ?>');background-size: cover;height: 300px;background-position: top;background-repeat: no-repeat;"></div>
      </a>
      <div class="content">
        <a class="header" style="text-decoration: none;" href="<?php echo get_permalink().'?id='.$user->ID; ?>">
          <b class="user_id"><?php echo $user->display_name; ?></b>
        </a>
        <div class="meta" style="color:black;">
          <?php 
          $birth = $user_meta['date_of_birth'][0];
          $today = date("d-m-Y");
          $age = date_diff(date_create($birth), date_create($today));
          echo 'DoB: '.date('d-M-Y',strtotime($birth)).' (Age: '.$age->format("%y").'yrs)'; ?>
        </div>
        <div class="description">
          <?php
          foreach ($fields as $field) {
            echo $field->post_title.': '.stripslashes(get_user_meta($user->ID,$field->post_name,true)).'<br>';
          }
          ?>
        </div>
      </div>
      <div class="extra content" style="color:blue; ">
        <b><?php echo 'ID: '.$user->ID; ?></b>
      </div>
    </div>
    <?php
  }
  ?>
</div>
<?php
if (!$blogusers) {
   echo '<h2 style="color:red; text-align:center">No '.$this_one.' users found!!!</h2>';
 } 
 ?>
<style type="text/css">
  .user_id{
    color: blue
  }
  form{
    display: inline-block;
  }
</style>