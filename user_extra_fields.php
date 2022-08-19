<?php
/* -- User can edit his own profile from dashboard with these lines. -- */
add_action( 'personal_options_update', 'save_extra_user_profile_fields_yno' );
add_action( 'show_user_profile', 'extra_user_profile_fields_yno' );

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
    ?>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.0.js"></script><!-- -- Semantic-UI CSS & JS files included here -- -->
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/button.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/table.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/dropdown.min.css">
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/transition.min.css">
<style type="text/css">
    div.ui.dropdown{
        min-height: 1em !important;
    }
</style>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/dropdown.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/components/transition.js"></script>

    <h3>Extra profile information</h3>
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
?>
<?php
/* Powered By Haysky Code Generator: KEY
[["select","gender,Male,Female"],["text","agent"],["submit","User Extra"]]
*/
?>