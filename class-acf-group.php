<?php
/**
 * Allows easy creation of groups and fields for the
 * Advanced Custom Fields plugin by Elliot Condon.
 * 
 * @since 0.1.0
 * @see https://www.advancedcustomfields.com/resources/register-fields-via-php/
 */
class ACF_Group {
	/**
	 * Holds the raw fields for the group, which will be processed
	 * before the group is registered with the plugin.
	 * 
	 * @since 0.1.0
	 * @var mixed[]
	 */
	protected $fields = array();

	/**
	 * Holds the ID of the group.
	 * 
	 * @since 0.1.0
	 * @var string
	 */
	protected $id;

	/**
	 * The title of the group.
	 * 
	 * @since 0.1.0
	 * @var string
	 */
	protected $title;

	/**
	 * Holds generic top-level attributes, like menu order, position and etc.
	 * 
	 * @since 0.1.0
	 * @var mixed[]
	 */
	protected $attributes = array();

	/**
	 * Holds an array of available locations.
	 * 
	 * @since 0.1.0
	 * @var ACF_Group_Location[]
	 */
	protected $locations = array();

	/**
	 * Creates a new group.
	 * 
	 * Important: Once the group is created, the ID must not ever change.
	 *            If you change the ID, there is a big data loss probability.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $id A unique ID, that will be used when creating field keys.
	 * @param string $title The title of the group, as it will be displayed in the title bar.
	 * @return ACF_Group
	 */
	public static function create( $id, $title ) {
		return new self( $id, $title );
	}

	/**
	 * Initializes a group.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $id A unique ID, that will be used when creating field keys.
	 * @param string $title The title of the group, as it will be displayed in the title bar.
	 */
	protected function __construct( $id, $title ) {
		$this->id    = $id;
		$this->title = $title;
	}

	/**
	 * Adds a location rule to the group.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $param    The parameter (ex. post_type)
	 * @param mixed  $value    The value (ex. post)
	 * @param string $operator The operator to compar ethe value (def. ==)
	 * @return ACF_Group       The instance of the object.
	 */
	public function add_location_rule( $param, $value, $operator = '==' ) {
		if( empty( $this->locations ) ) {
			$this->locations[] = new ACF_Group_Location;
		}

		$this->locations[ 0 ]->add_rule( $param, $value, $operator );

		return $this;
	}

	/**
	 * Pushes an entire location.
	 * 
	 * @since 0.1.0
	 * 
	 * @param ACF_Group_Location $location The location to add.
	 * @return ACF_Group The current object.
	 */
	public function add_location( ACF_Group_Location $location ) {
		$this->locations[] = $location;

		return $this;
	}

	/**
	 * Adds fields to the group.
	 * 
	 * @since 0.1.0
	 * 
	 * @param mixed[] $fields The fields to add. Check the docs for the format.
	 * @return ACF_Group The instance of the group.
	 */
	public function add_fields( $fields ) {
		foreach( $fields as $field ) {
			$this->fields[] = $field;
		}

		return $this;
	}

	/**
	 * Allows attributes to be changed.
	 *
	 * @since 0.1.0
	 * 
	 * @param string $key   The key of the attribute.
	 * @param mixed  $value The value of the attribute.
	 * @return ACF_Group
	 */
	public function set_attr( $key, $value = null ) {
		if( is_array( $key ) ) {
			foreach( $key as $k => $v ) {
				$this->attributes[ $k ] = $v;				
			}
		} else {
			$this->attributes[ $key ] = $value;			
		}

		return $this;
	}

	/**
	 * Once all data is in place, this method registers the group with ACF>
	 * 
	 * @since 0.1.0
	 */
	public function register() {
		$defaults = array(
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => 1,
			'description'           => ''
		);

		/**
		 * Allows the default arguments for a group to be modified.
		 * 
		 * @since 0.1.0
		 * 
		 * @param mixed[]   $defaults The default arguments for registering a group.
		 * @param ACF_Group $group    The group whose defaults are being modified.
		 * @return mixed[]
		 */
		$defaults = apply_filters( 'acf_group.defaults', $defaults, $this );

		$locations = array();
		foreach( $this->locations as $location ) {
			$locations[] = $location->get_rules();
		}

		$arguments = array_merge( $defaults, $this->attributes, array(
			'key'      => $this->id,
			'title'    => $this->title,
			'fields'   => $this->get_prepared_fields(),
			'location' => $locations
		));

		/**
		 * Allows the arguments for a group to be modified before
		 * acf_add_local_field_group() is called.
		 * 
		 * @since 0.1.0
		 * 
		 * @param mixed[]   $defaults The default arguments for registering a group.
		 * @param ACF_Group $group    The group whose defaults are being modified.
		 * @return mixed[]
		 */
		$arguments = apply_filters( 'acf_group.arguments', $arguments, $this );

		acf_add_local_field_group( $arguments );
	}

	/**
	 * Prepares a field for the group.
	 * 
	 * @since 0.1.0
	 * 
	 * @param mixed[] $field The basic field details.
	 * @param string $prefix A prefix that should be added to the field.
	 * @return mixed[] Full data about the field.
	 */
	protected function prepare_field( $field, $prefix = '', $parent_key = '' ) {
		static $defaults;

		if( is_null( $defaults ) ) {
			$defaults = array(
				'type'              => 'text',
				'instructions'      => '',
				'required'          => 0,
				'conditional_logic' => 0,
				'default_value'     => '',
				'placeholder'       => '',
				'wrapper'           => array(
					'width' => ''
				),
			);

			/**
			 * Allows the default arguments for a field to be modified.
			 * 
			 * @since 0.1.0
			 * 
			 * @param mixed[] $defaults The default values.
			 * @return mixed[]
			 */
			$defaults = apply_filters( 'acf_group.field.defaults', $defaults );
		}

		# Add either the group key or the prefix from above
		if( ! $prefix ) {
			$prefix = $this->id . '_';
		}

		$field[ 'key' ] = $prefix . $field[ 'name' ];
		$field = array_merge( $defaults, $field );

		# Let the conditional logic work
		if( $field[ 'conditional_logic' ] ) {
			$logic     = $field[ 'conditional_logic' ];
			$processed = array();

			# Allow one less level of declarations
			if( isset( $logic[ 0 ][ 'field' ] ) ) {
				$logic = array( $logic );
			}

			foreach( $logic as $group ) {
				$rules = array();

				foreach( $group as $rule ) {
					$rule[ 'field' ] = $prefix . $rule[ 'field' ];

					if( ! isset( $rule[ 'operator' ] ) ) {
						$rule[ 'operator' ] = '==';
					}

					if( ! isset( $rule[ 'value' ] ) ) {
						$rule[ 'value' ] = '1';
					}

                    # "Outside" option for conditional logic inside a repeater
                    # If set true, the target field will be outside of the repeater
                    # One level above, on the same level as the repeater field
                    if ( isset($rule['outside']) && $rule['outside'] && $parent_key ) {
                        # Removes only the last occurrence of the parent, in case it's present more than one time
                        $rule['field'] = preg_replace("~$parent_key(?!.*$parent_key)~", '', $rule['field']);
                        $rule['field'] = str_replace( '__', '_', $rule['field'] );
                    }

					$rules[] = $rule;
				}

				$processed[] = $rules;
			}

			$field[ 'conditional_logic' ] = $processed;
		}

		# Fixes/modifies specific field types.
		switch( $field[ 'type' ] ) {
			# Make sure that file fields always return an ID when using get_field()
			case 'image':
			case 'file':
				if( ! isset( $field[ 'return_format' ] ) ) {
					$field[ 'return_format' ] = 'id';			
				}
				break;

			# Fix the sub-fields of repeaters
			case 'repeater':
				$sub_fields = array();

				foreach( $field[ 'sub_fields' ] as $subfield ) {
                    # Passing the name as a third argument (parent name for the subfield)
                    # In case it's needed for conditional logic
					$sub_fields[] = $this->prepare_field( $subfield, $field[ 'key' ] . '_', $field['name'] );
				}

				$field[ 'sub_fields' ] = $sub_fields;
				break;

			# Adjust the flexible layout field
			case 'flexible_content':
				$layouts = array();

				foreach( $field[ 'layouts' ] as $layout ) {
					$sub_fields    = array();
					$layout_prefix = strtolower( $field[ 'key' ] . '_' . $layout[ 'key' ] . '_' );

					foreach( $layout[ 'sub_fields' ] as $subfield ) {
						$sub_fields[] = $this->prepare_field( $subfield, $layout_prefix );
					}

					$layout[ 'sub_fields' ] = $sub_fields;
				    $layouts[] = $layout;					
				}

				$field[ 'layouts' ] = $layouts;
				break;
		}

		return $field;
	}

	/**
	 * Prepares field before they get registered.
	 * 
	 * @since 0.1.0
	 * 
	 * @return mixed[]
	 */
	protected function get_prepared_fields() {
		$fields = array();

		foreach( $this->fields as $field ) {
			# Skip empty or incorrect fields
			if ( ! isset( $field['name'] ) || ! $field['name'] ) continue;
			$fields[] = $this->prepare_field( $field );
		}

		return $fields;
	}
}