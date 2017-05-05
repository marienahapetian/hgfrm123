<?php
/**
 * Plugin configurations
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

$hugeit_constants=array(
    'HUGE_FORMS_TEXT_DOMAIN'=>'huge-forms',
    'HUGE_FORMS_IMAGES_URL'=>untrailingslashit( plugins_url( '/', __FILE__ )  ) . '/assets/images/',
);

foreach($hugeit_constants as $key=>$constant) {
    if (!defined($key)) define($key,$constant);
}

/**
 * @param $classname
 * @throws Exception
 */
function huge_forms_autoload( $classname )
{
    /**
     * We do not touch classes that are not related to us
     */
    if ( !strstr( $classname, 'Huge_Forms_' ) ) {
        return;
    }


    if ( strstr( $classname, 'Huge_Forms_Field' ) ) {

        $path = Huge_Forms()->plugin_path().'/includes/classes/fields/class-'.str_replace('_','-',strtolower($classname)).'.php';

    } elseif( strstr( $classname, 'Huge_Forms_Admin' ) ) {

        $path = Huge_Forms()->plugin_path().'/includes/classes/admin/class-'.str_replace('_','-',strtolower($classname)).'.php';

    } else {

        $path = Huge_Forms()->plugin_path().'/includes/classes/class-'.str_replace('_','-',strtolower($classname)).'.php';

    }

    if ( !file_exists( $path ) ) {

        throw new Exception( 'the given path for class "'.$classname.'" is wrong, trying to load from '.$path );

    }

    require $path;

    if ( !interface_exists( $classname ) && !class_exists( $classname ) ) {

        throw new Exception( 'The class "'.$classname.'" is not declared in "'.$path.'" file.' );

    }
}


/* Register Autoload function for loading classes */
if ( function_exists( 'spl_autoload_register' ) ) {

    spl_autoload_register( 'huge_forms_autoload' );

} elseif ( isset( $GLOBALS['_wp_spl_autoloaders'] ) ) {

    array_push($GLOBALS['_wp_spl_autoloaders'], 'huge_forms_autoload');

} else {

    throw new Exception( 'Something went wrong, looks like your server does not support autoload functionality, please check your php version and upgrade WordPress to the latest version.' );

}



