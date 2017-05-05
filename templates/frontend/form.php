<?php
/*
 * $form Huge_Forms_Form
 */
?>
<div class="hgfrm-form-container">
    <?php if ($form->get_display_title()):?>
    <div class="hgfrm-form-title">
        <span><?php echo $form->get_name();?></span>
    </div>
    <?php endif;?>

    <form action="" method="" class="hgfrm-form" id="<?php echo $form->get_id();?>">
        <?php
        $fields = $form->get_fields();

        foreach ( $fields as $field ) {

            $field->field_html();

        }
        ?>
    </form>
</div>





