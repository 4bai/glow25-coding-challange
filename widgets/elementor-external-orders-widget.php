<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

class Elementor_External_Orders_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'external_orders';
	}

	public function get_title() {
		return esc_html__( 'WooCommerce External Orders', 'elementor-addon' );
	}

	public function get_icon() {
		return 'eicon-product-info';
	}

	public function get_categories() {
		return [ 'basic' ];
	}

	public function get_keywords() {
		return [ 'woocommerce', 'external', 'orders' ];
	}

	protected function render() {
		require_once( EXORDERS__PLUGIN_DIR . 'core/frontend/order-list.php' );
		display_orders();

	}

	protected function content_template() {
	}
}