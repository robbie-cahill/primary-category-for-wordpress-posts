<?php
/**
 * @package Primary_Category
 * @var $primary_category
 * @var $term
 * @var $nonce
 **/

namespace Robbie_Cahill\Primary_Category;

?>
<input type="hidden" id="primary-category-nonce" value="<?php echo esc_attr( $nonce ); ?>">
<select name="primary-category" class="primary-category">
	<?php if ( $term ) : ?>
			<option id="<?php echo esc_attr( $term->term_id ); ?> selected="selected"><?php echo esc_html( $term->name ); ?></option>
	<?php endif ?>
</select>
