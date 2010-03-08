<?php

/**
 * Common model
 *
 * Utilizes the Data Mapper pattern to persist data.
 *
 * @package    FelinPossible
 * @subpackage Model
 */
abstract class FP_Model_Bean_Common
{
	/**
	 * Constructor
	 *
	 * @param  array|null $options
	 * @return void
	 */
	public function __construct(array $options = null)
	{
		if (is_array($options)) {
			$this->setOptions($options);
		}
	}

	/**
	 * Overloading: allow property access
	 *
	 * @param  string $name
	 * @param  mixed $value
	 * @return void
	 */
	public function __set($name, $value)
	{
		$method = 'set' . $name;
		if (!method_exists($this, $method)) {
			echo "Invalid property specified : $name";
			throw Exception('Invalid property specified');
		}
		$this->$method($value);
	}

	/**
	 * Overloading: allow property access
	 *
	 * @param  string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		$method = 'get' . $name;
		if (!method_exists($this, $method)) {
			print_r($this);
			echo "Function inconnue : $name";
		}
		return $this->$method();
	}

	/**
	 * Set object attributes
	 *
	 * @param  array $options
	 * @return FP_Model_Common
	 */
	public function setOptions(array $options)
	{
		$methods = get_class_methods($this);
		foreach ($options as $key => $value) {
			$method = 'set' . ucfirst($key);
			if (in_array($method, $methods)) {
				$this->$method($value);
			}
		}
		return $this;
	}

	/**
	 *
	 * Convert an object to an array
	 *
	 * @param    object  $object The object to convert
	 * @reeturn      array
	 *
	 */
	public function  toArray( )
	{
		if( !is_object( $this ) && !is_array( $this ) )
		{
			return $this;
		}
		if( is_object( $this ) )
		{
			$object = get_object_vars( $this );
		}
		return array_map( NULL, $object );
	}
}
