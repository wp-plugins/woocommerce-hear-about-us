<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WCHAU_Admin_Setting_Fields
 *
 * Handles the WooCommerce Plivo settings. This uses the WooCommerce Settings API.
 *
 * @package WooCommerce_HearAboutUs
 * @class WCHAU_Admin_Setting_Fields
 * @author Pieter Carette <pieter@siteoptimo.com>
 */
class WCHAU_Admin_Setting_Fields {
	/**
	 * Construct the class.
	 */
	public function __construct() {

		add_filter( 'woocommerce_account_settings', array( $this, 'add_settings' ) );
	}

	public function add_settings( $settings ) {

		$title = array(
			'title' => __( 'Where did you hear about us', 'woocommerce-hear-about-us' ),
			'type'  => 'title',
			'desc'  => 'Manage the "where did you hear about us" options.',
			'id'    => 'wchau_title'
		);
		array_push( $settings, $title );

		$required = array(
			'title'   => __( 'Make it required', 'woocommerce-hear-about-us' ),
			'id'      => 'wchau_required',
			'type'    => 'checkbox',
			'default' => 'yes',
		);
		array_push( $settings, $required );

		$fields     = apply_filters( 'wchau_settings_fields', array(

				array(
					'title'    => __( 'Label', 'woocommerce-hear-about-us' ),
					'desc'     => __( 'Customize the "where did you hear about us" label.', 'woocommerce-hear-about-us' ),
					'id'       => 'wchau_label',
					'type'     => 'text',
					'default'  => __( 'Where did you hear about us?', 'woocommerce-hear-about-us' ),
					'desc_tip' => true,
				),
				array(
					'title'    => __( 'Possible answers', 'woocommerce-hear-about-us' ),
					'desc'     => __( 'List all of the possible answers, one answer per line.', 'woocommerce-hear-about-us' ),
					'id'       => 'wchau_options',
					'type'     => 'textarea',
					'default'  => implode( PHP_EOL, array( 'Google', 'Facebook', 'Twitter', 'A friend', 'Other' ) ),
					'desc_tip' => true,
				)
			)
		);
		$settings   = array_merge( $settings, $fields );
		$sectionend = array( 'type' => 'sectionend', 'id' => 'wchau_sectionend' );

		array_push( $settings, $sectionend );

		return $settings;
	}
} 