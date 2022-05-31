<link rel="stylesheet" type="text/css" href="/wp-content/plugins/veera-shaiva-marriage/home.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/card.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/icon.css">
<?php
$gender[0] = 'Female';
$gender[1] = 'Male';
$father[0] = 'D/o: ';
$father[1] = 'S/o: ';
for($m=0; $m < 2; $m++) {
    $args = array(
        'role'         => 'hs_matrimony_user',
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
            $img_id = $user_meta['photo'][0];
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
                <?php echo '<big style="color:blue"><b>'.$user->display_name.'</b></big>'; ?>
                <div class="description">
                    <?php echo '
                    Caste: '.$user_meta['religion'][0].'
                    <br><i class="handshake outline icon"></i>Job: '.$user_meta['job'][0].'
                    <br><i class="rupee sign icon"></i>Salary: '.$user_meta['salary'][0].'
                    <br><i class="birthday cake icon"></i>DOB: '.$user_meta['date_of_birth'][0].'
                    <br><i class="address book outline icon"></i>'.$father[$m].$user_meta['father'][0].'
                    <br><big style="color:blue"><b>ID: VSB'.$user->ID.'</b></big>';
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