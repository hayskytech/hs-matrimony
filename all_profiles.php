<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/table.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/icon.min.css">
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js"></script>
<?php
$wpdb;
if (!is_user_logged_in()) {
  ?>
  <style type="text/css">
    .matrimony-form{
      width: 50%;
      float: left;
    }
    .form-div{
      padding: 50px;
    }
    @media(max-width:670px){
      .matrimony-form{
        width: 100%;
      }
      .form-div{
        padding: 10px;
      }
    }
    .registerbutton,.logforb .lighte {
      padding: 20px !important;
    }
  </style>
  <!-- <div class="matrimony-form">
    <div class="form-div">
      <h3>Login with Password</h3>
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
    </div>
  </div> -->
  <div class="matrimony-form">
    <div class="form-div">
      <h3>Login</h3>
      <?php
      echo do_shortcode('[idehweb_lwp]');
      ?>
    </div>
  </div>
  <!-- <div class="matrimony-form">
    <div class="form-div">
      <h3>Register</h3>
      <?php
      // echo do_shortcode('[df-form-signup]');
      ?>
    </div>
  </div> -->
  <?php
  return;
}
if (isset($_GET['search-by-id'])){
  ?>
  <form class="matriform" style="padding:50px 0px">
    <b><big>View by ID:</big></b>
    <br>
    <select class="ui search dropdown" name="id">
      <option></option>
      <?php
      $search_args = array(
        'number'     => -1,
        'orderby' => 'rand',
        'role__not_in' => array('administrator','agent')
      );
      $options = get_users( $search_args );
      foreach ($options as $option) {
        $your_name = get_user_meta($option->ID, 'your_name', true);
        echo '<option value="'.$option->ID.'">'.$option->ID.' - '.$your_name.'</option>';
      }
      ?>
    </select>
    <button>Submit</button>
  </form>
  <script type="text/javascript">
    $('.ui.dropdown').dropdown();
  </script>
  <?php
  return;
}
if (isset($_GET['id']) || isset($_GET['my-profile']) || isset($_GET['add-user'])) {
  include 'my_profile.php';
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
if (isset($_GET['Community'])) {
  $this_comm = $_GET['Community'];
} else {
  $this_comm = 'All';
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
    'meta_query' => array(
      'relation' => 'AND',
      array(
          'key'     => 'gender',
          'value'   => $this_one
      ),
    ),
    'number'     => $number,
    'orderby' => 'rand',
    'role__not_in' => array('administrator','agent')
  );
  if (isset($_GET['Community'])){
    if ($this_comm!='All'){
      $comm_id = get_term_by('name', $this_comm, 'community')->term_id;
      $children = get_term_children($comm_id,'community');
      array_push($user_args['meta_query'],array(
        'relation' => 'OR',
        array(
          'key'     => 'community',
          'value'   => $this_comm
        )
      ));
      foreach($children as $child){
        array_push($user_args['meta_query'][1],array(
          'key'     => 'community',
          'value'   => get_term_by('term_id', $child, 'community')->name
        ));
      }
    }
  }
  // echo '<pre>';
  // print_r($user_args);
  // echo '</pre>';
  if (isset($_GET['agent'])) {
    $user_args['meta_query'] = array();
    $user_args['meta_key'] = 'agent';
    $user_args['meta_value'] = get_current_user_id();

  }
}
$blogusers = get_users( $user_args );
if (!$filter_hide) {
  ?>
  <h1>All Profiles</h1>
  <?php 
  echo '<big><a href="'.wp_logout_url( get_permalink() ).'"><b>Logout</b></a></big>'; ?>
  <div>
    <form class="matriform">
      <b><big>Gender:</big></b>
      <select name="Gender" id="gender-filter">
        <option>Male</option>
        <option>Female</option>
      </select>
      <b><big>Community:</big></b>
      <select name="Community" id="community-filter">
        <option value="All">All</option>
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
  <br>
  <?php
}
?>
<script type="text/javascript">
  $('select[name=Gender]').val('<?php echo $this_one; ?>')
  $('select[name=Community]').val('<?php echo $this_comm; ?>')
</script>
<div class="ui four doubling stackable cards">
  <?php
  $my_likes = (array) json_decode(get_user_meta($user_id,'interested',true));
  foreach ($blogusers as $user) {
    $user_meta = get_user_meta($user->ID);
    $img_id = $user_meta[$profile_pic][0];
    $img_url = wp_get_attachment_image_src($img_id,'large')[0];
    if(!$img_url){
      $img_url = 'https://www.gravatar.com/avatar/00000000000000000000000000000000?s=300&d=mp';
    }
    ?>
    <div class="card">
      <a href="<?php echo get_permalink().'?id='.$user->ID; ?>">
        <div style="background-image: url('<?php echo $img_url; ?>');background-size: cover;height: 300px;background-position: top;background-repeat: no-repeat;"></div>
      </a>
      <div class="content">
        <a class="header" style="text-decoration: none;" href="<?php echo get_permalink().'?id='.$user->ID; ?>">
          <b class="user_id"><?php echo $user_meta['your_name'][0]; ?></b>
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
<br>
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