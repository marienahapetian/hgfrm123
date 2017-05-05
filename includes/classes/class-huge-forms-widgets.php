<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Widgets
 */
class Huge_Forms_Widgets
{

    public static function init()
    {
        register_widget( 'Huge_Forms_Widget' );
    }

}