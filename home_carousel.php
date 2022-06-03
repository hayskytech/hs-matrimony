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
    $blogusers = get_users( $args );
    echo '<h2>'.$gender[$m].' Profiles: </h2>';
    ?>
    <div class="ui four doubling stackable cards">
        <?php
        $k = 0;
        foreach ($blogusers as $user) {
            $user_meta = get_user_meta($user->ID);
            $img_id = $user_meta[$profile_pic][0];
            $href_url = wp_get_attachment_image_src($user_meta[$profile_pic][0],'large')[0];
            if(!$img_id){
                continue;
            }
            $img_url = wp_get_attachment_image_src($img_id,'medium')[0];
            $k++;
            if ($k>12) {
                break;
            } 
            ?>
        <div class="card">
            <div class="image">
                <?php echo '<img src="'.$img_url.'">'; ?>
            </div>
            <div class="content">
                <?php echo '<big style="color:blue"><b>ID:'.$user->ID.' - '.$user->display_name.'</b></big>'; ?>
                <div class="description">
                    <?php
                    while ( $loop->have_posts() ) : $loop->the_post();
                        global $post;
                        echo $post->post_title.': '.get_user_meta($user->ID,$post->post_name,true).'<br>';
                    endwhile;
                    ?>
                </div>
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