<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wwdh.de
 * @since             1.0.0
 * @package           Hupa_Teams
 *
 * @wordpress-plugin
 * Plugin Name:       WP Team Members
 * Plugin URI:        https://wwdh.de/plugins
 * Description:       Team Plugin für WordPress
 * Donate link:       https://wwdh.de/donate
 * Version:           1.0.1
 * Stable tag:        1.0.1
 * Requires PHP:      7.4
 * Tested up to:      5.9.2
 * Requires at least: 5.6
 * Author:            Jens Wiecker
 * Author URI:        https://wwdh.de
 * License:           GPL3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       hupa-teams
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
$plugin_data = get_file_data(dirname(__FILE__) . '/hupa-teams.php', array('Version' => 'Version'), false);
define("HUPA_TEAMS_VERSION", $plugin_data['Version']);
/**
 * Currently DATABASE VERSION
 * @since             1.0.0
 */


const HUPA_TEAMS_DB_VERSION = '1.0.0';


/**
 * MIN PHP VERSION for Activate
 * @since             1.0.0
 */
const HUPA_TEAMS_PHP_VERSION = '7.4';

/**
 * MIN WordPress VERSION for Activate
 * @since             1.0.0
 */
const HUPA_TEAMS_WP_VERSION = '5.6';

/**
 * PLUGIN SLUG
 * @since             1.0.0
 */
define('HUPA_TEAMS_SLUG_PATH', plugin_basename(__FILE__));

/**
 * PLUGIN BASENAME
 * @since             1.0.0
 */
define('HUPA_TEAMS_BASENAME', plugin_basename(__DIR__));

/**
 * PLUGIN DIR
 * @since             1.0.0
 */
define('HUPA_TEAMS_DIR', dirname(__FILE__). DIRECTORY_SEPARATOR );

/**
 * PLUGIN ADMIN DIR
 * @since             1.0.0
 */
const HUPA_TEAMS_ADMIN_DIR = HUPA_TEAMS_DIR . 'admin' . DIRECTORY_SEPARATOR;

/**
 * PLUGIN Gutenberg Build DIR
 * @since             1.0.0
 */
const HUPA_TEAMS_SIDEBAR_BUILD_DIR = HUPA_TEAMS_ADMIN_DIR . 'gutenberg-sidebar' . DIRECTORY_SEPARATOR . 'sidebar-react' . DIRECTORY_SEPARATOR . 'build' . DIRECTORY_SEPARATOR;
const HUPA_TEAMS_BLOCK_BUILD_DIR = HUPA_TEAMS_ADMIN_DIR . 'gutenberg-block' . DIRECTORY_SEPARATOR  . 'build' . DIRECTORY_SEPARATOR;
const HUPA_TEAMS_GUTENBERG_LANGUAGE = HUPA_TEAMS_DIR . 'languages';

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-hupa-teams-activator.php
 */
function activate_hupa_teams() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hupa-teams-activator.php';
	Hupa_Teams_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-hupa-teams-deactivator.php
 */
function deactivate_hupa_teams() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-hupa-teams-deactivator.php';
	Hupa_Teams_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_hupa_teams' );
register_deactivation_hook( __FILE__, 'deactivate_hupa_teams' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-hupa-teams.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

global $hupa_team_members;
$hupa_team_members = new Hupa_Teams();
$hupa_team_members->run();
