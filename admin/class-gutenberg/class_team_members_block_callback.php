<?php

namespace Hupa\TeamMembers;

defined('ABSPATH') or die();

/**
 * ADMIN Gutenberg CALLBACK BLOCKTYPE
 * @package Hummelt & Partner WordPress-Plugin
 * Copyright 2022, Jens Wiecker
 * License: Commercial - goto https://www.hummelt-werbeagentur.de/
 *
 * @Since 1.0.0
 */
class Team_Members_Block_Callback
{

    /**
     * TRAIT of Default Settings.
     * @since    1.0.0
     */
    use Hupa_Teams_Members_Defaults_Trait;

    public static function callback_team_members_block_type($attributes)
    {
        if ($attributes) {
            ob_start();
            add_filter('render_block', array(Render_Callback_Templates::class, 'render_core_team_members_callback'), 0, 2);
            apply_filters(HUPA_TEAMS_BASENAME.'/render_callback_template', $attributes);
           return ob_get_clean();
        }
    }
}