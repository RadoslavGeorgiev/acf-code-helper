<?php
/**
 * Handles a location for ACF_Group.
 * 
 * @since 0.1.0
 */
class ACF_Group_Location {
	/**
	 * Holds the attributes of the location.
	 * 
	 * @since 0.1.0
	 * @var mixed[]
	 */
	protected $rules = array();

	/**
	 * Adds an attribute to the location.
	 * 
	 * @since 0.1.0
	 * 
	 * @param string $param    The parameter (ex. post_type)
	 * @param mixed  $value    The value (ex. post)
	 * @param string $operator The operator to compar ethe value (def. ==)
	 */
	public function add_rule( $param, $value, $operator = '==' ) {
		$this->rules[] = compact( 'param', 'value', 'operator' );

		return $this;
	}

	/**
	 * Returns all rules.
	 * 
	 * @since 0.1.0
	 * 
	 * @return mixed[]
	 */
	public function get_rules() {
		return $this->rules;
	}
}