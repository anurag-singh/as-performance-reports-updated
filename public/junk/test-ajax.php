<?php
/*require_once( dirname( dirname( __FILE__ ) ) . '/wp-load.php' );*/

		
$parse_uri = explode( 'wp-content', $_SERVER['SCRIPT_FILENAME'] );
require_once( $parse_uri[0] . 'wp-load.php' );
$status = 'fail';
global $wpdb;
$dump = $wpdb->get_results("SELECT * FROM ".$wpdb->prefix."comments");
print_r($dump);
/*if($wpdb)
{
	$status = 'done';
}*/
$array = array('status'=>$status);
echo json_encode($array);


?>