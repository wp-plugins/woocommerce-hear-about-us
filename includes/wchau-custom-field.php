<?php

class WCHAU_Custom_Field {

	public function __construct() {
		add_action( 'woocommerce_after_order_notes', array( $this, 'display_field' ) );
		add_action( 'woocommerce_checkout_process', array( $this, 'process_checkout_fields' ) );
		add_action( 'woocommerce_checkout_update_user_meta', array( $this, 'save_custom_checkout' ) );
		add_filter( 'woocommerce_customer_meta_fields', array( $this, 'user_profile' ) );
	}

	function display_field( $checkout ) {
		woocommerce_form_field( 'wchau_source', array(
			'type'     => 'select',
			'class'    => array( 'wchau-source form-row-wide' ),
			'label'    => wchau_get_option( 'wchau_label' ),
			'options'  => $this->get_options(),
			'required' => $this->is_field_required(),
		), $checkout->get_value( 'wchau_source' ) );

	}

	public static function prepare_options( $options ) {

		$options = explode( PHP_EOL, $options );

		$return = array();

		$return['empty'] = __( '-- Choose an option --', 'woocommerce-hear-about-us' );

		foreach ( $options as $option ) {
			$return[ self::slugify( $option ) ] = $option;
		}

		return $return;
	}

	public function process_checkout_fields() {
		if ( ! $this->is_field_required() ) {
			return;
		}

		if ( ! isset( $_POST['wchau_source'] ) || empty( $_POST['wchau_source'] ) || $_POST['wchau_source'] == 'empty' ) {
			wc_add_notice( __( 'Please enter where you found us.', 'woocommerce-hear-about-us' ), 'error' );
		}
	}

	function save_custom_checkout( $user_id ) {
		if ( ! empty( $_POST['wchau_source'] ) ) {

			$options = $this->get_options();

			$source = $_POST['wchau_source'];

			update_user_meta( $user_id, '_wchau_source', sanitize_text_field( isset( $options[ $source ] ) ? $options[ $source ] : '' ) );
		}
	}

	public function user_profile( $fields ) {
		$fields['wchau_source'] = array(
			'title'  => __( 'Where did you hear about us', 'woocommerce-hear-about-us' ),
			'fields' => array(
				'_wchau_source' => array(
					'label'       => __( 'Source', 'woocommerce-hear-about-us' ),
					'description' => ''
				),
			)
		);

		return $fields;
	}


	private function get_options() {
		return self::prepare_options( wchau_get_option( 'wchau_options' ) );
	}

	public static function slugify( $in ) {
		return sanitize_title_with_dashes( $in );
	}

	private function is_field_required() {
		return get_option( 'wchau_required', 'yes' ) == 'yes';
	}
}