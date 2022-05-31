<?php
/*** Sort and Filter Users ***/
add_action('restrict_manage_users', 'filter_by_gender');

function filter_by_gender($which){
 // template for filtering
 $st = '<select name="gender_%s" style="float:none;margin-left:10px;">
    <option value="">%s</option>%s</select>';

 // generate options
 $options = '
   <option value="Male">Male</option>
   <option value="Female">Female</option>';
 
 // combine template and options
 $select = sprintf( $st, $which, __( 'Gender...' ), $options );

 // output <select> and submit button
 echo $select;
 submit_button(__( 'Filter' ), null, $which, false);
}

add_filter('pre_get_users', 'filter_users_by_gender_section');

function filter_users_by_gender_section($query)
{
 global $pagenow;
 if (is_admin() && 'users.php' == $pagenow) {
  // figure out which button was clicked. The $which in filter_by_gender()
  $top = $_GET['gender_top'] ? $_GET['gender_top'] : null;
  $bottom = $_GET['gender_bottom'] ? $_GET['gender_bottom'] : null;
  if (!empty($top) OR !empty($bottom))
  {
   $section = !empty($top) ? $top : $bottom;
   
   // change the meta query based on which option was chosen
   $meta_query = array (array (
      'key' => 'gender',
      'value' => $section
   ));
   $query->set('meta_query', $meta_query);
  }
 }
}