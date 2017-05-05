<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Install
 */
class Huge_Forms_Install
{

    /**
     * If old tables exist will be true and we will need to run a database refactoring.
     *
     * @var bool
     */
    private static $needs_refactoring = false;

    /**
     * If plugin tables are created will be true, and default rows will be inserted, tables exist nothing will happen.
     *
     * @var bool
     */
    private static $create_default_rows_and_set_options;

    /**
     * Initialize
     */
    public static function init()
    {
        add_action( 'init', array( __CLASS__, 'check_version' ), 5 );
    }

    /**
     * Check Huge_Forms version and run the updater if required.
     *
     * This check is done on all requests and runs if the versions do not match.
     */
    public static function check_version()
    {
        if ( get_option( 'Huge_Forms_Version' ) !== Huge_Forms()->version ) {
            self::install();
            update_option( 'Huge_Forms_Version', Huge_Forms()->version );
            do_action( 'Huge_Forms_Version' );
        }
    }

    /**
     * Install required items for plugin
     */
    public static function install()
    {
        global $wpdb;

        if ( self::$needs_refactoring ) {

            self::run_database_refactor();

        } else {

            self::create_tables();

        }

        if ( self::$create_default_rows_and_set_options ) {

            try {

                self::insert_default_rows();

            } catch ( Exception $e ) {

                echo '<strong>' . $e->getMessage() . '</strong>';

            }

        }
    }

    /**
     * Uninstall plugin
     */
    public static function uninstall()
    {
        global $wpdb;

        if(Huge_Forms::delete_tables_uninstall()){
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('forms')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('fields')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('formFields')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('fieldTypes')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('themes')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('onSubmitActions')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('labelPositions')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('subscribers')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('submissions')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('submissionFields')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('blacklist')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('settings')."`");
            $wpdb->query("DROP TABLE IF EXISTS `".Huge_Forms()->get_table_name('fieldOptions')."`");
        }
    }

    /**
     * Create new tables and run database refactoring
     */
    private static function run_database_refactor()
    {
        global $wpdb;

        /**
         * First We need to create new tables
         */
        self::create_tables();

        Huge_Forms_DB_Refactor::init();

    }

    /**
     * Create Tables
     */
    private static function create_tables()
    {
        global $wpdb;

        if ( ! self::$needs_refactoring ) {

            $Huge_Forms_forms_exists = $wpdb->get_row( "SELECT * FROM information_schema.tables WHERE table_schema = '" . DB_NAME . "' AND table_name = '" . Huge_Forms()->get_table_name('forms') . "' LIMIT 1;", ARRAY_A );

            self::$create_default_rows_and_set_options = empty( $Huge_Forms_forms_exists );

        }

        $collate = '';

        if ( $wpdb->has_cap( 'collation' ) ) {

            $collate = $wpdb->get_charset_collate();

        }

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_field_types(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                name text DEFAULT NULL,
                
                PRIMARY KEY (id)
            ) ENGINE=InnoDB  {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_themes(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                name text DEFAULT NULL,
                
                PRIMARY KEY (id)
            ) ENGINE=InnoDB  {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_label_positions(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                name text DEFAULT NULL,
                
                PRIMARY KEY (id)
            ) ENGINE=InnoDB  {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_onsubmit_actions(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                name text DEFAULT NULL,
                
                PRIMARY KEY (id)
            ) ENGINE=InnoDB  {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_blacklist(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                subscriber_id int(11) UNSIGNED NOT NULL,
                email varchar(50) DEFAULT NULL,
                
                PRIMARY KEY (id)
            ) ENGINE=InnoDB {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_forms(
				id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
				name text DEFAULT NULL,
				display_title int(1) UNSIGNED NOT NULL DEFAULT '1',
				action_onsubmit int(11) UNSIGNED NOT NULL DEFAULT '1',
				labels_position int(11) UNSIGNED NOT NULL DEFAULT '1',
				theme int(5) UNSIGNED NOT NULL DEFAULT '1',
				email_user int(1) UNSIGNED NOT NULL DEFAULT '1',
				email_admin int(1) UNSIGNED NOT NULL DEFAULT '1',
				from_email varchar(50) DEFAULT NULL,
				from_name varchar(50) DEFAULT NULL,
				user_mail_subject varchar(255) DEFAULT 'You Submitted a Form',
				admin_mail_subject varchar(255) DEFAULT 'A Form Was Submitted on Your Website',
				user_mail_message text DEFAULT NULL,
				admin_mail_message text DEFAULT NULL,
				admin_email varchar(50) DEFAULT NULL,
				success_message varchar(100) DEFAULT NULL,
				email_format_error varchar(100) DEFAULT NULL,
				required_field_error varchar(100) DEFAULT NULL,
				upload_size_error varchar(100) DEFAULT NULL,
				upload_format_error varchar(100) DEFAULT NULL,
			  
				PRIMARY KEY (id),
				FOREIGN KEY (theme) REFERENCES " . $wpdb->prefix . "hgfrm_themes (id),
				FOREIGN KEY (labels_position) REFERENCES " . $wpdb->prefix . "hgfrm_label_positions (id) ON DELETE CASCADE,
				FOREIGN KEY (action_onsubmit) REFERENCES " . $wpdb->prefix . "hgfrm_onsubmit_actions (id) ON DELETE CASCADE
			) ENGINE=InnoDB  {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_fields(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                label varchar(100) DEFAULT NULL,
                label_position int(11) UNSIGNED NOT NULL DEFAULT '1',
                required int(1) DEFAULT '0',
                disabled int(1) DEFAULT '0',
                class varchar(30) DEFAULT NULL,
                container_class varchar(30) DEFAULT NULL,
                helper_text text DEFAULT NULL,
                type int(11) UNSIGNED DEFAULT '1',
                default_value text DEFAULT NULL,
                placeholder varchar(255) DEFAULT NULL,
                ordering int(3) DEFAULT '0',
                resizable int(1) DEFAULT '0',
                limit_number int(10) DEFAULT '10',
                limit_type varchar(4) DEFAULT 'char',
                minimum float(12) DEFAULT '0',
                maximum float(12) DEFAULT '10000',
                number_type varchar(5) DEFAULT 'int',
                option_type varchar(12) DEFAULT NULL,
                map_center varchar(100) DEFAULT NULL,
                draggable int(1) DEFAULT 1,
                recaptcha_type varchar(8) DEFAULT NULL,
                recaptcha_style varchar(5) DEFAULT NULL,
                
                PRIMARY KEY (id),
                FOREIGN KEY (label_position) REFERENCES " . $wpdb->prefix . "hgfrm_label_positions (id) ON DELETE CASCADE,
                FOREIGN KEY (type) REFERENCES " . $wpdb->prefix . "hgfrm_field_types (id) ON DELETE CASCADE
            ) ENGINE=InnoDB {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_form_fields(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                form int(11) UNSIGNED DEFAULT NULL,
                field int(11) UNSIGNED DEFAULT NULL,
                                
                PRIMARY KEY (id),
                FOREIGN KEY (form) REFERENCES " . $wpdb->prefix . "hgfrm_forms (id) ON DELETE CASCADE,
				FOREIGN KEY (field) REFERENCES " . $wpdb->prefix . "hgfrm_fields (id) ON DELETE CASCADE
            ) ENGINE=InnoDB  {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_field_options(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                name varchar(255) DEFAULT NULL,
                value varchar(11) DEFAULT NULL,
                field int(11) UNSIGNED DEFAULT NULL,
                checked int(1) DEFAULT '0',
                ordering int(3) DEFAULT '0',
                                
                PRIMARY KEY (id),
				FOREIGN KEY (field) REFERENCES " . $wpdb->prefix . "hgfrm_fields (id) ON DELETE CASCADE
            ) ENGINE=InnoDB  {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_address_field_options(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                field int(11) UNSIGNED NOT NULL,
                show_country int(1) DEFAULT 1,
                placeholder_country int(11) UNSIGNED DEFAULT NULL,
                countries text DEFAULT NULL,
                show_state int(1) DEFAULT 1,
                show_city int(1) DEFAULT 1,
                show_address int(1) DEFAULT 1,
                show_zip int(1) DEFAULT 1,
                                
                PRIMARY KEY (id),
				FOREIGN KEY (field) REFERENCES " . $wpdb->prefix . "hgfrm_fields (id) ON DELETE CASCADE
            ) ENGINE=InnoDB  {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_settings(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                name varchar(50)  DEFAULT NULL,
                value varchar(50)  DEFAULT NULL,
                                
                PRIMARY KEY (id)
            ) ENGINE=InnoDB  {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_subscribers(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                username varchar(50) DEFAULT NULL,
                email varchar(50) DEFAULT NULL,
                ip varchar(50) DEFAULT NULL,
                
                PRIMARY KEY (id)
            )ENGINE=InnoDB {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_submissions(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                user int(11) UNSIGNED DEFAULT '1',
                date datetime DEFAULT CURRENT_TIMESTAMP,
                form int(11) UNSIGNED DEFAULT NULL,
                
                PRIMARY KEY (id),
                FOREIGN KEY (user) REFERENCES " . $wpdb->prefix . "hgfrm_subscribers (id) ON DELETE CASCADE,
                FOREIGN KEY (form) REFERENCES " . $wpdb->prefix . "hgfrm_forms (id) ON DELETE CASCADE
            ) ENGINE=InnoDB {$collate}"
        );

        $wpdb->query(
            "CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "hgfrm_submission_fields(
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                submission int(11) UNSIGNED DEFAULT NULL,
                field int(11) UNSIGNED NOT NULL,
                value text DEFAULT NULL,
                
                PRIMARY KEY (id),
                FOREIGN KEY (field) REFERENCES " . $wpdb->prefix . "hgfrm_fields (id) ON DELETE CASCADE,
                FOREIGN KEY (submission) REFERENCES " . $wpdb->prefix . "hgfrm_submissions (id) ON DELETE CASCADE
            ) ENGINE=InnoDB {$collate}"
        );

        self::alter_tables();

    }

    private static function alter_tables()
    {

    }


    /**
     * Check if table row exists
     *
     * @param null $table_name
     * @param null $column_name
     * @return bool
     */
    private static function table_row_exists( $table_name = null, $column_name = null )
    {
        global $wpdb;
        $sql    = $wpdb->get_results( "SHOW columns FROM ".$table_name."" );
        $exists = false;

        foreach ( $sql as $a ) {

            if ( $a->Field == $column_name ) {

                $exists = true;

            }

        }

        return $exists;
    }


    /**
     * Handle the default raws
     */
    private static function insert_default_rows()
    {

        self::insert_default_forms();

        self::insert_default_fields();

        self::insert_default_field_types();

        self::insert_default_themes();

        self::insert_default_label_positions();

        self::insert_default_onsubmit_actions();

        self::insert_default_users();

    }

    /**
     * Insert default forms
     *
     * @return bool|int|null
     */
    private static function insert_default_forms(){

    }

    /**
     * Insert default fields
     *
     * @return bool|int|null
     */
    private static function insert_default_fields(){

    }

    /**
     * Insert default users
     *
     * @return bool|int|null
     */
    private static function insert_default_users(){

        $users=array('guest');

        foreach ($users as $user){

            $new_user = new Huge_Forms_Subscriber();

            $new_user->set_username( __( $user, HUGE_FORMS_TEXT_DOMAIN ) );

            $new_user->save();
        }

    }

    /**
     * Insert default field types
     *
     * @return bool|int|null
     */
    private static function insert_default_field_types(){
        $defaultTypes=array(
            'text','email','number',
            'textarea','radio','checkbox',
            'selectbox','date','recaptcha',
            'map','captcha','buttons',
            'hidden','license','html',
            'password','phone','address',
            'upload'
        );
        foreach ($defaultTypes as $defaultType){
            $field_type = new Huge_Forms_Field_Type();

            $field_type->set_name( __( $defaultType, HUGE_FORMS_TEXT_DOMAIN ) );

            $field_type->save();
        }
    }

    /**
     * Insert default form themes
     *
     * @return bool|int|null
     */
    private static function insert_default_themes(){
        $theme = new Huge_Forms_Theme();

        $theme->set_name( __( 'Default', HUGE_FORMS_TEXT_DOMAIN ) );

        $saved_theme = $theme->save();

    }

    /**
     * Insert default label positions
     *
     * @return bool|int|null
     */
    private static function insert_default_label_positions(){
        $defaultPositions=array('default','left','right','above','inside','hidden');

        foreach ($defaultPositions as $defaultPosition) {

            $label_position = new Huge_Forms_Label_Position();

            $label_position->set_name(__($defaultPosition, HUGE_FORMS_TEXT_DOMAIN));

            $label_position->save();
        }
    }

    /**
     * Insert default onsubmit actions
     *
     * @return bool|int|null
     */
    private static function insert_default_onsubmit_actions(){
        $onsubmit_action = new Huge_Forms_Onsubmit_Action();

        $onsubmit_action->set_name( __( 'Success Message', HUGE_FORMS_TEXT_DOMAIN ) );

        $onsubmit_action->save();
    }

    /**
     * Delete tables on uninstall
     *
     * @return bool
     */
    private static function delete_tables_uninstall()
    {
        if(Huge_Forms::get_setting('remove-tables-uninstall')=='on'){
            return true;
        } else{
            return false;
        }
    }

}




































