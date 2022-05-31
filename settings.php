<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.css">
<script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.js"></script>
<script type="text/javascript" src="https://semantic-ui.com/javascript/library/tablesort.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/dropdown.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/transition.js"></script>
<h1>Settings</h1>
<?php
global $wpdb;
$user_id = get_current_user_id();
if(isset($_POST["submit"])){
    $data["disable_captcha"] = $_POST["disable_captcha"];
    foreach ($data as $key => $value) {
        update_option($key, $value);
    }
    ?>
    <script type="text/javascript">
        window.location.href = "";
    </script>
    <?php
}
?>
<form method="post" enctype="multipart/form-data">
    <table class="ui collapsing striped table">
        <tr>
        <td>Disable Captcha</td>
        <td><select class="ui search dropdown" name="disable_captcha">
                <option value="">Select</option>
                <option value="1">Disable</option>
                <option value="0">Enable</option>
            </select>
            <script type="text/javascript">
                $(".ui.dropdown").dropdown();
            </script>
        </td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="submit" value="Save" class="ui blue mini button"></td>
        </tr>
    </table>
</form>
<?php
$data["disable_captcha"] = get_option("disable_captcha");
?>
<script type="text/javascript">
    $('select[name=disable_captcha]').val('<?php echo $data["disable_captcha"]; ?>');
    x = $('select[name=disable_captcha]').children('option[value="<?php echo $data["disable_captcha"]; ?>"]').text();
    $("select[name=user]").parent().children(".text").html(x);
    y = $('select[name=disable_captcha]').parent().children(".text");
    y.html(x);
    y.css("color","black");
</script>