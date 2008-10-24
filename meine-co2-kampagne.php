<?php
/*
Plugin Name: Meine CO2 Kampagen
Plugin URI: http://www.co2kampagne.de
Description: Adds co2 campaign-conunter of your "co2 campaign" on your sidebar or any page.
Version: 1.0
Author: CO2 Kampagne
Author URI: http://www.co2kampagne.de

-----------------------------------------------------
Copyright 2006  CO2 Kampagne  (email : info@co2kampagne.de)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
-----------------------------------------------------

See readme file for change-logs.
*/

// This gets called at the plugins_loaded action
function widget_co2kampagne_init() {
	
	// Check for the required API functions
	if ( !function_exists('register_sidebar_widget') || !function_exists('register_widget_control') ){
		return;	
	}

	// This saves options and prints the widget's config form.
	function widget_co2kampagne_control() {
		$options = $newoptions = get_option('widget_co2kampagne');
		if ( $_POST['co2kampagne-submit'] ) {
			$newoptions['title'] = $_POST['co2kampagne-title'];
			$newoptions['width'] = (int) $_POST['co2kampagne-width'];
			$newoptions['height'] = (int) $_POST['co2kampagne-height'];
			$newoptions['url'] = $_POST['co2kampagne-url'];	
      $newoptions['abstand'] = (int) $_POST['co2kampagne-abstand'];		
      $newoptions['vertical'] = (int) $_POST['co2kampagne-vertical'];		
		}

		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('widget_co2kampagne', $options);
		}
		?>
		
		<div id="blind_link" 
style="position:absolute; z-index:1; left:0px;top:0px;width:210px;height:108px;cursor:pointer;" onclick="window.open('https://shop.co2kampagne.de/mit/<?php $url = $options['url'];	 echo $url; ?> ','_blank');return(false)"></div>
		<iframe src="/wp-content/plugins/my-co2-campaign/zummitmachen.gif" style="border:0px #FFFFFF none;" name="co2kampagne" scrolling="no" frameborder="0" align=left marginheight="0px" marginwidth="0px" height="130" width="260"></iframe>
		<div style="text-align:right">
		
		    <p style="text-align:left;"><label for="co2kampagne-intro"></label></p>
			<label for="co2kampagne-title" style="line-height:35px;display:block; text-align:left;">Kurz-Text: <br><input type="text" id="co2kampagne-title" name="co2kampagne-title" value="<?php echo ($options['title']); ?>" /></label><br>
			<label for="co2kampagne-abstand" style="line-height:12px;display:block; text-align:left;">Horizontale Position <br>(z.B. -10 => Z&auml;hler eher links, 10 => Z&auml;hler eher rechts): <br><br><input type="text" id="co2kampagne-abstand" name="co2kampagne-abstand" value="<?php echo ($options['abstand']); ?>" /></label><br>
			<label for="co2kampagne-vertical" style="line-height:12px;display:block; text-align:left;">Vertikale Position <br>(Abstand nach Unten (z.B. 10): <br><br><input type="text" id="co2kampagne-vertical" name="co2kampagne-vertical" value="<?php echo ($options['vertical']); ?>" /></label>
      <label for="co2kampagne-url" style="line-height:35px;display:block; text-align:left;">Deine Shop-URL: <input type="text" id="co2kampagne-url" name="co2kampagne-url" value="<?php echo $options['url']; ?>" /></label><br>
      <label for="co2kampagne-link" style="line-height:12px;display:block; text-align:left;">	<a href="http://shop.co2kampagne.de/partnershops">Starte Deine CO2 Kampagne und hole Deine Shop-URL</a></label><br>
			<input type="hidden" name="co2kampagne-submit" id="co2kampagne-submit" value="1" />
		</div>
		<?php
	}

	// This prints the widget
	function widget_co2kampagne($args) {	
		extract($args);
		$defaults = array('title' => 'Meine CO2 Kampagne', 'width' => 211, 'height' => 108, 'url' => 'https://shop.co2kampagne.de/?p=co2wert&shopversion=standard', 'abstand' => '0', 'vertical' => '0');
		$options = (array) get_option('widget_co2kampagne');

		//If the user has not yet set the options or set them empty, take the defaults
		foreach ( $defaults as $key => $value ){
			if ( !isset($options[$key]) || $options[$key] == ""){
				$options[$key] = $defaults[$key];	
			}
		}
		
		$title = $options['title'];
		$width = $options['width'];
		$height = $options['height'];
		$url = $options['url'];		
		$abstand = $options['abstand'];
		$vertical = $options['vertical'];
		?>
		

		<div style="position:relative; margin-bottom:<?php echo $options['vertical']; ?>px;">
<div id="blind_link" 
style="position:absolute; z-index:100; left:0px;top:0px;width:200px;height:130px;cursor:pointer;" onclick="window.open('https://shop.co2kampagne.de/mit/<?php echo $url; ?> ','_blank');return(false)"></div>
		<?php echo $before_widget . $before_title . $title . $after_title; ?>
		<iFrame style="position:relative; z-index:10; margin-left:<?php echo $abstand; ?>px;" scrolling="no" frameborder="0" src="https://shop.co2kampagne.de/?p=co2wert&shopversion=<?php echo $url; ?>" width="<?php echo $width; ?>px" height="<?php echo $height; ?>px">The browser doesn't support IFrames.</iFrame>
		<?php echo $after_widget; ?></div>
		<?php
	}

	// Tell Dynamic Sidebar about our new widget and its control
	register_sidebar_widget('Meine CO2 Kampagne', 'widget_co2kampagne');
	register_widget_control('Meine CO2 Kampagne', 'widget_co2kampagne_control');
}

//Converts all the occurances of [co2kampagne][/co2kampagne] to  HTML tags

function widget_co2kampagne_on_page($text){
	$regex = '#\[co2kampagne]((?:[^\[]|\[(?!/?co2kampagne])|(?R))+)\[/co2kampagne]#';
	if (is_array($text)) {
	    $param = explode(",", $text[1]);
		//generate the Counter tag
        $text = '<iFrame  height="108" width="211" scrolling="no" frameborder="0" src="https://shop.co2kampagne.de/?p=co2wert&shopversion=/'.$param[0].'"'.$others.'></iFrame>';
    }
	return preg_replace_callback($regex, 'widget_co2kampagne_on_page', $text);
}

// Delay plugin execution to ensure Dynamic Sidebar has a chance to load first
add_action('plugins_loaded', 'widget_co2kampagne_init');
add_filter('the_content', 'widget_co2kampagne_on_page');
?>