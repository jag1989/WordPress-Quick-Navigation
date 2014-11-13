<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    WP_QN
 * @subpackage WP_QN/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    WP_QN
 * @subpackage WP_QN/admin
 * @author     Jonathan Garrett <jag1989@gmail.com>
 */
class WP_QN_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $WP_QN    The ID of this plugin.
	 */
	private $WP_QN;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $WP_QN       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $WP_QN, $version ) {

		$this->WP_QN = $WP_QN;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the Dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_QN_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_QN_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->WP_QN, plugin_dir_url( __FILE__ ) . 'css/wp-qn-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the dashboard.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WP_QN_Admin_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WP_QN_Admin_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->WP_QN, plugin_dir_url( __FILE__ ) . 'js/wp-qn-admin.js', array( 'jquery' ), $this->version, false );

	}

	public function dropdown_pages( $wp_admin_bar ) {

		global $post;
		$temp_p = $post;
		$id = 'qn_pages';

		$args = array(
			'post_type' => 'page',
			'post_status' => array(
				'publish', 'pending', 'draft', 'future', 'private', 'inherit'
			),
			'posts_per_page' => -1,
			'orderby' => 'name',
			'order' => 'ASC',
			'post_parent' => 0,
			'orderby' => 'name',
			'order' => 'ASC'
		);
		$listpages = new WP_Query( $args );

		if ( $listpages->have_posts() ) :

			$args = array(
				'id'    => $id,
				'title' => 'Jump to Page',
				'meta'  => array(
					'class' => 'wp-qn-container'
				)
			);
			$wp_admin_bar->add_node( $args );

			while ( $listpages->have_posts() ) : $listpages->the_post();

				$this->listdropdown($wp_admin_bar, $post);

			endwhile;
		endif;

		wp_reset_postdata();
		$post = $temp_p;

	}


	private function listdropdown( $wp_admin_bar, $post, $id){
		$children =  get_pages( array ('child_of' => $post->ID ) );
		if( count( $children ) == 0 ) :
			$args = array(
				'id'    => get_the_ID(),
				'title' => get_the_title(),
				'href'  => get_edit_post_link(),
				'parent' => $id
			);
			$wp_admin_bar->add_node( $args );
		else :
			$args = array(
				'id'    => get_the_ID().'group',
				'title' => get_the_title(),
				'href'  => get_edit_post_link(),
				'parent' => $id
			);
			$wp_admin_bar->add_group( $args );

			$args = array(
				'id'    => get_the_ID(),
				'title' => get_the_title(),
				'href'  => get_edit_post_link(),
				'parent' => get_the_ID().'group'
			);
			$wp_admin_bar->add_node( $args );

			foreach($children as $child):
				$args = array(
					'id'    => $child->ID,
					'title' => get_the_title($child->ID),
					'href'  => get_edit_post_link($child->ID),
					'parent' => $child->post_parent
				);
				$wp_admin_bar->add_node( $args );
			endforeach;
		endif;
	}

}
