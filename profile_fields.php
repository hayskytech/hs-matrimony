<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
<!-- -- Semantic-UI CSS & JS files included here -- -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/button.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/table.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/icon.min.css">
<style type="text/css">
    div.ui.dropdown{
        min-height: 1em !important;
    }
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/dropdown.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/transition.js"></script>

<h1>Settings</h1>
<?php
global $wpdb;
$user_id = get_current_user_id();
if(isset($_POST["submit"])){
    $data["matrimony_fields"] = $_POST["matrimony_fields"];
    $data["matrimony_photos"] = $_POST["matrimony_photos"];
    foreach ($data as $key => $value) {
        update_option($key, $value);
    }
}
?>
<form method="post" enctype="multipart/form-data">
    <table class="ui collapsing striped table">
        <tr>
            <td>Matrimony Fields</td>
            <td><textarea name="matrimony_fields" rows="20" cols="100"><?php
            echo get_option("matrimony_fields"); ?></textarea>
            </td>
        </tr>
        <tr>
            <td>Matrimony Photos</td>
            <td><input type="text" name="matrimony_photos" >
            </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Save" class="ui blue mini button"></td>
        </tr>
    </table>
</form>
<?php
$data["matrimony_photos"] = get_option("matrimony_photos");
?>
<script type="text/javascript">
    jQuery('input[name=matrimony_photos]').val('<?php echo $data["matrimony_photos"]; ?>');
</script>
<script type="text/javascript">
    if ( window.history.replaceState ) {
        window.history.replaceState( null, null, window.location.href );
    }
</script>
<?php
/* Powered By Haysky Code Generator: KEY
[["text","matrimony_fields"],["submit","Settings"]]
*/
?>