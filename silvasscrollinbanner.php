<?php
/*
Plugin Name: Silvas Scroll in Banner
Plugin URL: http://www.silvas.nl
Version: 0.1
Author: Timon Bos
Author URL: http://www.silvas.nl
Description: Shows Banner slide in after x seconds, just once a session
*/

function register_session(){

  	if( !session_id() )
        session_start(); 
	
}
add_action('init','register_session');

add_action('wp_head','hook_css');

function hook_css() {

	$output="
	<style>
body {
overflow-x: hidden;
}

.slideinbanner
{
	position: absolute;
	-moz-transition: all 0.3s ease-in;
	-webkit-transition: all 0.3s ease-in;
	-ms-transition: all 0.3s ease-in;
	transition: all 0.3s ease-in;
	z-index: 99999;
	".esc_attr( get_option('slideinextracss'))."
}

</style>";

	echo $output;
}

function showslideinbanner() {
	

	if ($_SESSION['bannershow'] == "" || $_SESSION['bannershow'] != "ja" || get_option('slideinip') == $_SERVER['REMOTE_ADDR'])
	{

?>
<div class='slideinbanner'>
<?PHP
print get_option('slideintext');
?>
</div>

<script>
  setTimeout(
    function() {
		jQuery(".slideinbanner").css("right", "0px"); 
    }, <?PHP print get_option('slideinna', 1000); ?>);
	
  setTimeout(
    function() {
		jQuery(".slideinbanner").css("display", "none"); 
    }, <?PHP print get_option('slideinuit', 10000); ?>);	
</script>
<?PHP
	$_SESSION['bannershow'] = "ja";
	}
	else
	{
		
	}
}
add_action( 'wp_footer', 'showslideinbanner' );


// create custom plugin settings menu
add_action('admin_menu', 'silvasscrollinbannermenu');

function silvasscrollinbannermenu() {

	//create new top-level menu
	add_menu_page('Scroll in', 'Slide Instellingen', 'administrator', __FILE__, 'bekijkdeinstellingenslidein' , 'dashicons-leftright' );

	//call register settings function
	add_action( 'admin_init', 'register_slidein_settings' );
}


function register_slidein_settings() {
	//register our settings
	register_setting( 'silvasslideinbanner', 'slideinextracss' );
	register_setting( 'silvasslideinbanner', 'slideintext' );
	register_setting( 'silvasslideinbanner', 'slideinip' );
	register_setting( 'silvasslideinbanner', 'slideinna' );
	register_setting( 'silvasslideinbanner', 'slideinuit' );

}

function bekijkdeinstellingenslidein() {
?>
<div class="wrap">
<h2>Silvas Slide-in Banner</h2>

<form method="post" action="options.php">
    <?php settings_fields( 'silvasslideinbanner' ); ?>
    <?php do_settings_sections( 'silvasslideinbanner' ); ?>
    <table class="form-table" style='width: 100%;'>
        <tr valign="top">
        <th scope="row">Additional CSS</th>
        <td><textarea style='width: 100%; height: 400px;' name="slideinextracss"><?php echo esc_attr( get_option('slideinextracss'), 'top: 225px;
right: -500px;
width: 300px;
height: 40px;
background-color: #C1001F;
padding: 15px;
color: white;
font-family: Lato, Arial !important;
font-size: 14px;' ); ?></textarea></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Contents of banner (html allowed)</th>
        <td><textarea style='width: 100%; height: 400px;' name="slideintext"><?php echo esc_attr( get_option('slideintext') ); ?></textarea></td>
        </tr>
<tr valign="top">
        <th scope="row">IP adress of administrator (for testing purposes)</th>
        <td><input type="text" name="slideinip" value="<?php echo esc_attr( get_option('slideinip', $_SERVER['REMOTE_ADDR']) ); ?>" /></td>
        </tr>
<tr valign="top">
        <th scope="row">Scroll in after Xms</th>
        <td><input type="text" name="slideinna" value="<?php echo esc_attr( get_option('slideinna',1000) ); ?>" /></td>
        </tr>		
		<tr valign="top">
        <th scope="row">Scroll out after Xms</th>
        <td><input type="text" name="slideinuit" value="<?php echo esc_attr( get_option('slideinuit',10000) ); ?>" /></td>
        </tr>
    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php } ?>