<?php
/**
 * Plugin Name:       Wordsys Form
 * Plugin URI:        https://www.wordsystech.com/wordsys-form
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            wordsystech
 * Author URI:        https://www.wordsystech.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       plugin-name
 * Domain Path:       /languages
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}
define( 'WORDSYSFORM_VERSION', '1.0.0' );

function activate_wordsysform() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordsysform-activator.php';
	Wordsysform_Activator::activate();
}

function deactivate_wordsysform() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wordsysform-deactivator.php';
	Wordsysform_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wordsysform' );
register_deactivation_hook( __FILE__, 'deactivate_wordsysform' );

/**
* The core plugin class that is used to define admin-specific hooks, and public-facing site hooks.
*/
require plugin_dir_path( __FILE__ ) . 'includes/class-wordsysform.php';
function run_wordsysform() {
	$plugin = new Wordsysform();
	$plugin->run();
}
run_wordsysform();
