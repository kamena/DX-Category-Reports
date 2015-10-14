<?php

/**
 * Database manager for processing reports, dates and all
 * 
 * @author nofearinc
 *
 */
class DX_Database_Time_Manager {
	
	/**
	 * Grouping years and months from the WordPress Core's data table component.
	 * 
	 * @param string $post_type post type in need.
	 * 
	 * @return $date_intervals an array with all month/year combinations of published posts.
	 */
	public static function get_months_for_post_type( $post_type = 'post' ) {
		global $wpdb;
		
		// Additional checks that could be injected in the query if need be
		$extra_checks = apply_filters( 'dxcr_months_request_filter', '' );
		
		$date_intervals = $wpdb->get_results( $wpdb->prepare( "
				SELECT DISTINCT YEAR( post_date ) AS year, MONTH( post_date ) AS month
				FROM $wpdb->posts
				WHERE post_type = %s
				$extra_checks
				ORDER BY post_date DESC
				", $post_type ) );
			
		return $date_intervals;
	}
	
	/**
	 * Pull a list of all published posts by category and grouped by month and year.
	 * 
	 * @param int $category_id the term ID to work with
	 * @param string $post_type the post type (default for posts)
	 * 
	 * @return $posts the list of post entries submitted per category for each month
	 */
	public static function get_cpt_category_report( $category_id, $post_type = 'post' ) {
		global $wpdb;
		
		// Fetch the list of posts in a category in order to match them before grouping
		$posts = DX_Database_Time_Manager::get_post_ids_in_a_category( $category_id );
		
		if ( empty( $posts ) ) {
			return array();
		}
		
		// Prepare the list of posts
		$posts_list = implode( ',', array_map( 'absint', $posts ) );
		
		// Generate the report
		$report = $wpdb->get_results( $wpdb->prepare( 
				"SELECT YEAR(post_date) as year, MONTH(post_date) as month, COUNT(*) as count 
				FROM $wpdb->posts WHERE post_type = '%s' 
				AND post_status='publish'
				AND ID IN ( $posts_list )
				GROUP by YEAR(post_date), MONTH(post_date)", 
				$post_type ) );
		
		return $report;
	}

	/**
	 * Pull a number of post IDs per category in order to match the results for self::get_cpt_category_report.
	 * 
	 * A reasonable limit of 5000 posts/category is set, but a filter exists for overriding those.
	 * 
	 * @param int $category_id the term ID
	 * @return $posts a list of all IDs used for matching posts in a given category above
	 */
	public static function get_post_ids_in_a_category( $category_id ) {
		
		// Filter as needed
		$posts_per_page = apply_filters( 'dxcr_posts_per_page_in_category', 5000 );
		
		$posts_in_category = new WP_Query(array(
			'posts_per_page' => $posts_per_page,
			'fields'         => 'ids',
			'cat'            => $category_id	
		));
		
		return $posts_in_category->posts;
	}
} 