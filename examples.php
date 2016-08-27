<?php
add_action( 'register_acf_groups', function() {
	ACF_Group::create( 'page_fields', 'Page Fields' )
		->add_location_rule( 'post_type', 'page' )
		->set_attr( 'label_placement', 'left' )
		->add_fields(array(
			array(
				'name'    => 'checkbox_field',
				'label'   => 'Check me',
				'type'    => 'true_false',
				'message' => 'Check me'
			),
			array(
				'name'              => 'featured_media',
				'label'             => 'Featured Media',
				'type'              => 'url',
				'conditional_logic' => array(
					array(
						'field' => 'checkbox_field'
					)
				)
			),
			array(
				'name'       => 'repeater_field',
				'label'      => 'Repeater field',
				'type'       => 'repeater',
				'sub_fields' => array(
					array(
						'label' => __( 'Text Sub Field', 'dw' ),
						'name'  => 'text_sub_field',
						'type'  => 'text'
					),
					array(
						'label' => __( 'Another Field', 'dw' ),
						'name'  => 'another_field',
						'type'  => 'text'
					),
					array(
					    'label' => __( 'Test Field', 'dw' ),
					    'name'  => 'test_field',
					    'type'  => 'text'
					),
				)
			)
		))
		->register();
});