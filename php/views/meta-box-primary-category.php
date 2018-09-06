<?php
    /**
     * @var $primary_category
     * @var $term
     */
?>
<select name="primary-category" class="primary-category">
    <?php if ( $term ): ?>
           <option id="<?php echo esc_attr( $term->term_id ) ?> selected="selected"><?php echo esc_html( $term->name )  ?></option>
    <?php endif ?>
</select>