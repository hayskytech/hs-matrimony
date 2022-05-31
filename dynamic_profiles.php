<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/table.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/icon.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/modal.min.css">

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script type="text/javascript" src="http://semantic-ui.com/javascript/library/tablesort.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/components/modal.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.3.3/semantic.min.js"></script>

<?php
$wpdb;
$user_id = get_current_user_id();
$gender = get_user_meta($user_id, 'gender', true);
if (current_user_can('hs_matrimony_user') || current_user_can('administrator')) {
  if($gender == 'Male'){
    $this_one = 'Female';
  } else {
    $this_one = 'Male';
  }
  if ($likers_page) {
    $likers = get_user_meta($user_id,'likers',true);
    $likers = json_decode($likers);
    $args = array(
      'role'         => 'hs_matrimony_user',
      'include'     =>  $likers
    );
  } else {
    $args = array(
      'role'         => 'hs_matrimony_user',
      'meta_key'     => 'gender',
      'meta_value'   => $this_one
    );
  }
    $blogusers = get_users( $args );
}
?>
<table class="ui celled sortable table">
  <thead>
    <tr>
      <th style="width:250px">Photo</th>
      <th>Name & Contact</th>
    </tr>
  </thead>
<?php
if (current_user_can('hs_matrimony_user') || current_user_can('administrator')) {
  $my_likes = (array) json_decode(get_user_meta($user_id,'interested',true));
  foreach ($blogusers as $user) {
    $i++;
    if (in_array($user->ID, $my_likes)) {
      $color = 'green';
      if ($likers_page) {
        $bg = 'style="background-color:skyblue"';
      }
      $like = 'no';
    } else {
      $color = 'grey';
      $like = 'yes';
    }
      $user_meta = get_user_meta($user->ID);
      $img_url = wp_get_attachment_image_src($user_meta['photo'][0],'medium')[0];
      $href_url = wp_get_attachment_image_src($user_meta['photo'][0],'large')[0];
      ?>
      <tr <?php echo $bg; ?>>
        <td style="text-align: center;">
        <a href="<?php echo $href_url; ?>" target="_blank">
          <img src="<?php echo $img_url; ?>" width="250">
        </a>
      </td>
        <?php echo '<td><div style="font-size:120%; line-height: 150%;">
        <b class="user_id">ID: '.$user->ID.'</b><br>
        '.$user->display_name.'
        <br>Caste: '.$user_meta['religion'][0].'
        <br><i class="handshake outline icon"></i>Job: '.$user_meta['job'][0].'
        <br><i class="rupee sign icon"></i>Salary: '.$user_meta['salary'][0].'
        <br><i class="birthday cake icon"></i>DOB: '.$user_meta['date_of_birth'][0].'
        <br><i class="arrows alternate vertical icon"></i>Height: '.$user_meta['height'][0].'
        <br><i class="address book outline"></i>
            <span style="text-decoration:underline">Father name:</span> '.$user_meta['father_name'][0].'
        <br>Place: '.$user_meta['town'][0].'
        </div>
      <div class="int_btn">';
      if ($bg) {
        echo '<b>Match found</b><br>';
      }
      /*
      echo '
        <b>
        <a href="/view?ID=VSB'.$user->ID.'&like='.$like.'" target="blank"><button class="ui '.$color.' button" onclick="green(this)">
          <i class="ui heart white icon"></i>Like</button>
        </a>
        </b>';
      */
      echo '</div>
      </td>
    </tr>';
  }
}
if (!$blogusers) {
   echo '<tr><td colspan="4"><h2 style="color:red; text-align:center">No users found!!!</h2></td></tr>';
 } 
 ?>
</table>
<script type="text/javascript">
$('table').tablesort();
function green(x){
  if ($(x).hasClass('grey')) {
    $(x).addClass('green');
    $(x).removeClass('grey');
  } else {
    $(x).addClass('grey');
    $(x).removeClass('green');
  }
}
</script>
<style type="text/css">
  .user_id{
    color: blue
  }
  .ui.grey.button a,
  .ui.green.button a{ 
    color: white 
  }
  div.int_btn{
    padding: 5px;
  }
</style>