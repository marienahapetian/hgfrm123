<?php
/**
 * @var $forms Huge_Forms_Form[]
 */

$first_form = $forms[0];
?>

<form method="post" action="" >
    <h3>Select The Form</h3>

    <select id="huge_form_select" >
        <?php
        foreach ( $forms as $form ) {
            ?>
                <option value="<?php echo $form->get_id(); ?>"><?php echo $form->get_name(); ?></option>
            <?php
        }
        ?>
    </select>
    <button class='button primary' id='huge_form_insert'><?php _e( 'Insert Form', HUGE_FORMS_TEXT_DOMAIN ); ?></button>
</form>
