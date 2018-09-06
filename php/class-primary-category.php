<?php
namespace Robbie_Cahill\Primary_Category;

class Primary_Category {

	/**
	 * @action admin_enqueue_scripts
	 */
	public function admin_enqueue_scripts() {
		wp_enqueue_style( 'select2-css', plugin_dir_url( __FILE__ ) . '../node_modules/select2/dist/css/select2.css' );
		wp_enqueue_style( 'primary-category-for-posts-css', plugin_dir_url( __FILE__ ) . '../assets/css/primary-category.css' );
		wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . '../node_modules/select2/dist/js/select2.full.js', [ 'jquery' ], '0.0.1', true );
		wp_enqueue_script( 'primary-category-for-posts', plugin_dir_url( __FILE__ ) . '../assets/js/primary-category.js', [ 'select2', 'jquery' ], '0.0.1', true );
	}

	public function add_meta_box( $post ) {
		add_meta_box(
			'primary-category-meta-box',
			'Primary Category',
			[ $this, 'render_primary_category_meta_box' ],
			'post',
			'normal',
			'default'
		);
	}

	public function admin_ajax_primary_category_query() {
		$results = [
			[
				'id' => 1,
				'text' => 'Lifestyle',
			],
			[
				'id' => 2,
				'text' => 'Home',
			],
		];

		$categories = new \stdClass();
		$categories->results = $results;

		wp_send_json( $categories );
	}

	public function render_primary_category_meta_box() {
		require __DIR__ . '/views/meta-box-primary-category.php';
	}
}
