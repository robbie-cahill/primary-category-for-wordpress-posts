<?php
namespace Robbie_Cahill\Primary_Category;

class Primary_Category {
	const ASSETS_VERSION = '0.0.1';

	/**
	 * @action admin_enqueue_scripts
	 */
	public function admin_enqueue_scripts() : void {
		wp_enqueue_style( 'select2-css', plugin_dir_url( __FILE__ ) . '../node_modules/select2/dist/css/select2.css' );
		wp_enqueue_style( 'primary-category-for-posts-css', plugin_dir_url( __FILE__ ) . '../assets/css/primary-category.css' );
		wp_enqueue_script( 'select2', plugin_dir_url( __FILE__ ) . '../node_modules/select2/dist/js/select2.full.js', [ 'jquery' ], self::ASSETS_VERSION, true );
		wp_enqueue_script( 'primary-category-for-posts', plugin_dir_url( __FILE__ ) . '../assets/js/primary-category.js', [ 'select2', 'jquery' ], self::ASSETS_VERSION, true );
	}

	/**
	 * Add the Primary Category meta box
	 * @param \WP_Post $post
	 */
	public function add_meta_box( \WP_Post $post ) : void {
		add_meta_box(
			'primary-category-meta-box',
			'Primary Category',
			[ $this, 'render_primary_category_meta_box' ],
			'post',
			'normal',
			'default'
		);
	}

	/**
	 * Select2 compatible AJAX query. Queries categories using the 'term' query var sent by Select2
	 *
	 * For smart people who know how to pull of a CSRF attack, this will give you a nice read only list of categories that are going to be public anyway on the front end
	 * Because the categories are public information, I chose not to add nonce verification here.
	 * This makes Select2's live search alot faster as a new nonce would have to be generated on each keypress.
	 *
	 * For usability purposes, I'm allowing all categories to come back as results, not just categories that are selected for this post
	 * This makes selecting a primary category a one step process
	 * The user will only have to publish the post once after selecting a category and primary category, not select a category, then publish, select a primary category then publish again.
	 */
	public function admin_ajax_primary_category_query() : void {
		$name = filter_input( INPUT_GET, 'term', FILTER_SANITIZE_STRING ); // Select2 sends "term" for the search query by default

		/**
		 * Return an empty reponse if the lenght of the queried name is under 2 characters or if non alphabetical characters are sent
		 */
		if (
			strlen( $name ) < 2 ||
			! preg_match( '/^[a-zA-Z]+$/', $name )
		) {
			wp_send_json( [] );
		}

		$results = [];

		/**
		 * For this use case, caching in memcache or another cahing server will be slower than using a direct query
		 * Especially if the category name is indexed in MySQL
		 * This is because multiple calls to the caching server would be made on every keypress
		 */
		$terms = get_terms(
			[
				'taxonomy' => 'category',
				'search' => $name,
			]
		);

		foreach ( $terms as $term ) {
			$result = [
				'id' => $term->term_taxonomy_id,
				'text' => $term->name,
			];

			$results[] = $result;
		}

		$categories = new \stdClass();
		$categories->results = $results;

		wp_send_json( $categories );
	}

	/**
	 * @action save_post
	 */
	public function process_primary_category() : void {
		$post_id = intval( filter_input( INPUT_POST, 'post_ID', FILTER_SANITIZE_NUMBER_INT ) );
		$primary_category = intval( filter_input( INPUT_POST, 'primary-category', FILTER_SANITIZE_NUMBER_INT ) );

		if ( 0 === $primary_category || 0 === $post_id ) {
			return;
		}

		/**
		 * I chose to store a primary category id inside post_meta
		 * Why didn't use a custom taxonomy?
		 * This is going to be queried from the frontend, which potentially means hundreds of millions of tax_queries if I use a custom taxonomy
		 * A tax_query involves a table join of multiple tables in the background
		 * A simple meta_query is going to be way faster over hundreds of millions of requests as only a single table needs to be queried
		 * Literally "SELECT post_id from wp_<blog_id>_post_meta WHERE meta_name='primary_category' AND meta_value=1"
		 */
		update_post_meta( $post_id, 'primary_category', $primary_category );
	}

	/**
	 * Render the Primary Category meta box
	 *
	 * Since its messy to mix PHP with HTML inside classes, the view code is in a seperate view file
	 */
	public function render_primary_category_meta_box( $post ) : void {
		/**
		 * I chose to store a primary category id inside post_meta
		 * Why didn't use a custom taxonomy?
		 * This is going to be queried from the frontend, which potentially means hundreds of millions of tax_queries if I use a custom taxonomy
		 * A tax_query involves a table join of multiple tables in the background
		 * A simple meta_query is going to be way faster over hundreds of millions of requests as only a single table needs to be queried
		 * Literally "SELECT post_id from wp_<blog_id>_post_meta WHERE meta_name='primary_category' AND meta_value=1"
		 */
		$primary_category = get_post_meta( $post->ID, 'primary_category', true );
		$term_id = null;

		if ( $primary_category ) {
			$terms = get_terms(
				[
					'taxonomy' => 'category',
					'term_taxonomy_id' => $primary_category,
				]
			);

			if ( isset( $terms[0] ) ) {
				$term = $terms[0];
			}
		}

		require __DIR__ . '/views/meta-box-primary-category.php';
	}
}
