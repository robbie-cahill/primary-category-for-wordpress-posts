<?php
namespace Robbie_Cahill\Primary_Category;

class Primary_Category {

	/**
	 * @action admin_init
	 */
	public function admin_init() {

	}

	public function adding_custom_meta_boxes( $post ) {
		add_meta_box(
			'my-meta-box',
			'Primary Category',
			[ $this, 'render_primary_category_meta_box' ],
			'post',
			'normal',
			'default'
		);
	}

	public function render_primary_category_meta_box() {
		require __DIR__ . '/views/meta-box-primary-category.php';
	}
}
