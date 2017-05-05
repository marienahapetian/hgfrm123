<?php

if (!defined( 'ABSPATH' )) exit;

/**
 * Class Huge_Forms_Field_Captcha
 */
class Huge_Forms_Field_Captcha extends Huge_Forms_Field
{
    public function __construct($id = null)
    {
        parent::__construct($id);
    }

    public function settings_block()
    {
        $settings_block_html='<div class="settings-block" id="'.$this->id.'">';
        $settings_block_html .= Huge_Forms_Admin_Setting::label_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::label_position_setting_row( $this );

        $settings_block_html.='<div class="setting-row"><label>Default Value</label> <input type="text" class="setting-default" name="default-'.$this->id.'" value="'.$this->default.'"></div>';
        $settings_block_html.='<div class="setting-row"><label>Placeholder </label><input type="text" class="setting-placeholder" name="placeholder-'.$this->id.'" value="'.$this->placeholder.'"></div>';
        $settings_block_html .= Huge_Forms_Admin_Setting::order_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::helptext_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::class_setting_row( $this );
        $settings_block_html .= Huge_Forms_Admin_Setting::container_class_setting_row( $this );
        $settings_block_html.='</div>';

        return $settings_block_html;
    }

    public function field_html()
    {
        ?>
            <div class="hgfrm-form-field <?php echo $this->field_class();?> <?php echo $this->container_class;?>">
                <label for="">
                    <?php echo $this->get_label();?>
                </label>
                <?php $this->help_text_block();?>

                <div class="huge-forms-captcha-box clear-float <?php echo $this->class;?>">
                    <img src="<?php echo $this->huge_forms_create_simple_captcha($this->id, 'user'); ?>"> <a href="" captchaid="<?php echo $this->id;?>"><img src="<?php echo HUGE_FORMS_IMAGES_URL;?>refresh-captcha.png"> </a>
                    <input type="text" placeholder="Type the code above">
                </div>
            </div>
        <?php
    }




    public static function huge_forms_create_simple_captcha($captcha_id='',$from=''){

        $upload_dir=wp_upload_dir();

        if (!file_exists($upload_dir['basedir']."/huge_forms_tmp")) {
            mkdir($upload_dir['basedir']."/huge_forms_tmp", 0777, true);
        }

        $current_dir = getcwd(); // Save the current directory
        $dir = $upload_dir['basedir']."/huge_forms_tmp/";

        chdir($dir);
        /*** cycle through all files in the directory ***/
        foreach (glob($dir."*") as $file) {
            /*** if file is 1/2 hours (1800 seconds) old then delete it ***/
            if (filemtime($file) < time() - 1800) {
                unlink($file);
            }
        }
        chdir($current_dir); // Restore the old working directory

        $is_ajax_request=false;

        if(isset($_POST['captchaid'])){
            $captcha_id=$_POST['captchaid'];
            $from='user';
            $is_ajax_request=true;
        }


        $time = time();

        $captcha='';

        for($i=1;$i<=8;$i++){
            $randnumber=rand(65,122);
            while(in_array($randnumber,array(91,92,93,94,95,96))){
                $randnumber=rand(65,122);
            }
            $captcha.=chr($randnumber);
        }

        $font_size=30;

        $_SESSION['huge_forms_captcha-'.$from.'-'.$captcha_id.'-'.$captcha_id.$time]=$captcha;

        $font = HUGE_FORMS_FONTS_PATH.'dirty_classic_machine.ttf';
        $image=imagecreatetruecolor(205,75);

        $black=imagecolorallocate($image,90,97,106);
        $white=imagecolorallocate($image,255,255,255);

        imagefilledrectangle($image,0,0,205,100,$white);

        imagettftext($image,$font_size,0,20,45,$black,$font,$captcha);

        $filename='captcha-'.$from.'-'.md5($captcha_id.$time).'.png';

        imagepng($image,$dir.'/'.$filename);

        if($is_ajax_request){
            wp_send_json($upload_dir['baseurl']."/huge_forms_tmp/".$filename);
        }

        return $upload_dir['baseurl']."/huge_forms_tmp/".$filename;

    }
}