<link rel="stylesheet" type="text/css" href="/wp-content/plugins/veera-shaiva-marriage/home.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/card.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/icon.css">
<?php
$gender[0] = 'Female';
$gender[1] = 'Male';
$father[0] = 'D/o: ';
$father[1] = 'S/o: ';
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
    ),
    array(
      'key' => 'matrimony_field_type',
      'value'   => array('image'),
      'compare' => 'NOT IN'
    )
  )
);
$loop = new WP_Query( $args );
for($m=0; $m < 2; $m++) {
    $args = array(
        'meta_key'     => 'gender',
        'meta_value'   => $gender[$m],
        'orderby' => 'rand',
        'number'  => 50
    );
    echo '<h2>'.$gender[$m].' Profiles: </h2>';
    ?>
    <div class="ui four doubling stackable cards">
      <?php
      $k = 0;
      $profiles = get_users( $args );
      foreach ($profiles as $user) {
          $user_meta = get_user_meta($user->ID);
          if ($profile_pic) {
              $img_id = $user_meta[$profile_pic][0];
              $href_url = wp_get_attachment_image_src($user_meta[$profile_pic][0],'large')[0];
              if(!$img_id){
                $img_url = 'https://www.gravatar.com/avatar/00000000000000000000000000000000?s=300&d=mp';
              } else {
                $img_url = wp_get_attachment_image_src($img_id,'medium')[0];
              }
          }
          $k++;
          if ($k>12) {
              break;
          } 
          ?>
          <div class="card">
            <a href="<?php echo $href_url; ?>" target="_blank">
              <div style="background-image: url('<?php echo $img_url; ?>');background-size: cover;height: 300px;background-position: center;background-repeat: no-repeat;"></div>
            </a>
            <div class="content">
              <a class="header" style="text-decoration: none;">
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
                while ( $loop->have_posts() ) : $loop->the_post();
                  global $post;
                  echo $post->post_title.': '.stripslashes(get_user_meta($user->ID,$post->post_name,true)).'<br>';
                endwhile;
                ?>
              </div>
            </div>
            <div class="extra content" style="color:blue; ">
              <b><?php echo 'ID: '.$user->ID; ?></b>
            </div>
          </div>
      <?php } ?>
    </div>
    <hr>
    <?php
}
?>
<style type="text/css">
.ui.card>.image>img, .ui.cards>.card>.image>img {
    height: 300px !important;
    width: auto !important;
    margin: auto !important;
}
.ui.card>.image>img, .ui.cards>.card>.image {
    margin: auto !important;
}
</style>