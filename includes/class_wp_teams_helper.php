<?php
namespace Hupa\TeamMembers;
use Exception;
use Hupa_Teams;

/**
 * Default Plugin Settings
 *
 * @link       https://wwdh.de
 * @since      1.0.0
 *
 * @package    Wp_Experience_Reports
 * @subpackage Wp_Experience_Reports/includes
 */
defined('ABSPATH') or die();
/**
 * @since      1.0.0
 * @package    Wp_Experience_Reports
 * @subpackage Wp_Experience_Reports/includes
 * @author     Jens Wiecker <email@jenswiecker.de>
 */

class Wp_Teams_Helper
{
    /**
     * The plugin Slug Path.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string $plugin_dir plugin Slug Path.
     */
    protected string $plugin_dir;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $basename The ID of this plugin.
     */
    private string $basename;

    /**
     * The Version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current Version of this plugin.
     */
    private string $version;

    /**
     * Store plugin main class to allow public access.
     *
     * @since    1.0.0
     * @access   private
     * @var Hupa_Teams $main The main class.
     */
    private Hupa_Teams $main;

    /**
     * Store plugin helper class.
     *
     * @param string $basename
     * @param string $version
     *
     * @since    1.0.0
     * @access   private
     *
     * @var Hupa_Teams $main
     */

    public function __construct( string $basename, string $version,  Hupa_Teams $main ) {

        $this->basename   = $basename;
        $this->version    = $version;
        $this->main       = $main;

    }

    /**
     * @throws Exception
     */
    public function getRandomString(): string
    {
        if (function_exists('random_bytes')) {
            $bytes = random_bytes(16);
            $str = bin2hex($bytes);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $bytes = openssl_random_pseudo_bytes(16);
            $str = bin2hex($bytes);
        } else {
            $str = md5(uniqid('post_selector_rand', true));
        }

        return $str;
    }

    /**
     * @param int $passwordlength
     * @param int $numNonAlpha
     * @param int $numNumberChars
     * @param bool $useCapitalLetter
     * @return string
     */
    public function getGenerateRandomId(int $passwordlength = 12, int $numNonAlpha = 1, int $numNumberChars = 4, bool $useCapitalLetter = true): string
    {
        $numberChars = '123456789';
        //$specialChars = '!$&?*-:.,+@_';
        $specialChars = '!$%&=?*-;.,+~@_';
        $secureChars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz';
        $stack = $secureChars;
        if ($useCapitalLetter == true) {
            $stack .= strtoupper($secureChars);
        }
        $count = $passwordlength - $numNonAlpha - $numNumberChars;
        $temp = str_shuffle($stack);
        $stack = substr($temp, 0, $count);
        if ($numNonAlpha > 0) {
            $temp = str_shuffle($specialChars);
            $stack .= substr($temp, 0, $numNonAlpha);
        }
        if ($numNumberChars > 0) {
            $temp = str_shuffle($numberChars);
            $stack .= substr($temp, 0, $numNumberChars);
        }

        return str_shuffle($stack);
    }

    /**
     * @param $name
     * @param bool $base64
     * @param bool $data
     *
     * @return string
     */
    public function svg_icons($name, bool $base64 = true, bool $data = true): string {
        $icon = '';
        switch ($name){
            case 'layer':
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="ps2-icon" viewBox="0 0 16 16">
  						  <path d="M8.235 1.559a.5.5 0 0 0-.47 0l-7.5 4a.5.5 0 0 0 0 .882L3.188 8 .264 9.559a.5.5 0 0 0 0 .882l7.5 4a.5.5 0 0 0 .47 0l7.5-4a.5.5 0 0 0 0-.882L12.813 8l2.922-1.559a.5.5 0 0 0 0-.882l-7.5-4zM8 9.433 1.562 6 8 2.567 14.438 6 8 9.433z"/>
						 </svg>';
                break;
            case'personen':
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-people" viewBox="0 0 16 16">
                         <path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0zM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816zM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275zM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4z"/>
                         </svg>';
                break;
            case'square':
                $icon = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="black" class="er-chat-square-text" viewBox="0 0 16 16">
                         <path d="M14 1a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1h-2.5a2 2 0 0 0-1.6.8L8 14.333 6.1 11.8a2 2 0 0 0-1.6-.8H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h12zM2 0a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h2.5a1 1 0 0 1 .8.4l1.9 2.533a1 1 0 0 0 1.6 0l1.9-2.533a1 1 0 0 1 .8-.4H14a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2z"/>
                         <path d="M3 3.5a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9a.5.5 0 0 1-.5-.5zM3 6a.5.5 0 0 1 .5-.5h9a.5.5 0 0 1 0 1h-9A.5.5 0 0 1 3 6zm0 2.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5z"/>
                          </svg>';
        }
        if($base64){
            if ($data){
                return 'data:image/svg+xml;base64,'. base64_encode($icon);
            }
            return base64_encode($icon);
        }
        return $icon;
    }

    /**
     * @param $array
     * @return object
     */
    public function ArrayToObject($array):object {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $array[$key] = self::ArrayToObject($value);
            }
        }
        return (object)$array;
    }
}