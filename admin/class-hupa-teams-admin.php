<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://wwdh.de
 * @since      1.0.0
 *
 * @package    Hupa_Teams
 * @subpackage Hupa_Teams/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Hupa_Teams
 * @subpackage Hupa_Teams/admin
 * @author     Jens Wiecker <email@jenswiecker.de>
 */
class Hupa_Teams_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $basename    The ID of this plugin.
	 */
	private string $basename;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private string $version;

    /**
     * Store plugin main class to allow public access.
     *
     * @since    1.0.0
     * @access   private
     * @var Hupa_Teams $main The main class.
     */
    private  Hupa_Teams $main;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param      string    $plugin_name The name of this plugin.
	 * @param string $version    The version of this plugin.
	 *@since    1.0.0
	 */
	public function __construct(string $plugin_name, string $version,Hupa_Teams $main ) {

		$this->basename = $plugin_name;
		$this->version = $version;
        $this->main = $main;
	}

    /**
     * Register the Update-Checker for the Plugin.
     *
     * @since    1.0.0
     */
    public function hupa_teams_update_checker() {

        if(get_option("{$this->basename}_server_api") && get_option($this->basename.'_server_api')->update->update_aktiv) {
            $postSelectorUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
                get_option("{$this->basename}_server_api")->update->update_url_git,
                WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . $this->basename . DIRECTORY_SEPARATOR . $this->basename . '.php',
                $this->basename
            );

            if (get_option("{$this->basename}_server_api")->update->update_type == '1') {
                if (get_option("{$this->basename}_server_api")->update->update_branch == 'release') {
                    $postSelectorUpdateChecker->getVcsApi()->enableReleaseAssets();
                } else {
                    $postSelectorUpdateChecker->setBranch(get_option("{$this->basename}_server_api")->update->branch_name);
                }
            }
        }
    }

    public function hupa_teams_show_upgrade_notification( $current_plugin_metadata, $new_plugin_metadata ) {

        /**
         * Check "upgrade_notice" in readme.txt.
         *
         * Eg.:
         * == Upgrade Notice ==
         * = 20180624 = <- new version
         * Notice		<- message
         *
         */
        if ( isset( $new_plugin_metadata->upgrade_notice ) && strlen( trim( $new_plugin_metadata->upgrade_notice ) ) > 0 ) {

            // Display "upgrade_notice".
            echo sprintf( '<span style="background-color:#d54e21;padding:10px;color:#f9f9f9;margin-top:10px;display:block;"><strong>%1$s: </strong>%2$s</span>', esc_attr( 'Important Upgrade Notice', 'post-selector' ), esc_html( rtrim( $new_plugin_metadata->upgrade_notice ) ) );

        }
    }


}
