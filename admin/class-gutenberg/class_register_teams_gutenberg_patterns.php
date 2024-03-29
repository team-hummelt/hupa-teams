<?php

namespace Hupa\TeamMembers;

use Hupa_Teams;
use Hupa\TeamMembers\Team_Members_Block_Callback;

defined('ABSPATH') or die();

/**
 * ADMIN Gutenberg Patterns
 * @package Hummelt & Partner WordPress-Plugin
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 *
 * @Since 1.0.0
 */
class Register_Teams_Gutenberg_Patterns
{

    protected Hupa_Teams $main;
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $basename The ID of this plugin.
     */
    private string $basename;

    /**
     * TRAIT of Default Settings.
     * @since    1.0.0
     */
    use Hupa_Teams_Members_Defaults_Trait;


    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private string $version;

    /**
     * @param string $plugin_name
     * @param string $version
     * @param Hupa_Teams $main
     */
    public function __construct(string $plugin_name, string $version, Hupa_Teams $main)
    {

        $this->basename = $plugin_name;
        $this->version = $version;
        $this->main = $main;

    }

    /**
     * Register WP_TEAM Block Patterns
     *
     * @since    1.0.0
     */
    public function register_gutenberg_patterns()
    {
        $tempDir = plugin_dir_path((__FILE__)) . 'gutenberg-block-patterns' . DIRECTORY_SEPARATOR;
        $patternOne = file_get_contents($tempDir . 'TemplateOne.html');
        $patternOne = str_replace('###PLACEHOLDERIMAGE###', plugins_url(HUPA_TEAMS_BASENAME).'/admin/images/placeholder-voll.png', $patternOne);
        register_block_pattern(
            'hupa/team-members-block-pattern',
            [
                'title' => __('Member Template one', 'hupa-teams'),
                'description' => _x('Template one for team members', 'Block pattern description', 'hupa-teams'),
                'content' => $patternOne,
                'categories' => [
                    'hupa/member-block-patterns',
                ],
            ],
        );
    }


    public function get_template_gutenberg_select($templateId = ''): array
    {
        $templates = [
            '0' => [
                'id' => 1,
                'name' => __('Member Template one', 'hupa-teams'),
                'file' => 'TemplateOne.html'
            ]
        ];

        if($templateId){
            foreach ($templates as $tmp){
                if ($tmp['id'] == $templateId){
                    return $tmp;
                }
            }
        }

        return $templates;
    }

    /**
     * Register WP_TEAM Block Pattern Category
     *
     * @since    1.0.0
     */
    public function register_block_pattern_category()
    {
        register_block_pattern_category(
            'hupa/member-block-patterns',
            [
                'label' => __('Team Members Patterns', 'hupa-teams'),
            ]
        );
    }
}