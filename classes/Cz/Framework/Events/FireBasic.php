<?php
namespace Cz\Framework\Events;

/**
 * FireBasic
 * 
 * Basic `fireEvent` implementation.
 * 
 * @package    Framework
 * @category   Events
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
trait FireBasic
{
	/**
	 * Fires event of a specific type with specified arguments as-is.
	 * 
	 * @param   string  $type       Event type to fire.
	 * @param   array   $arguments  Arguments to send to event handlers.
	 * @return  $this
	 */
	public function fireEvent($type, array $arguments)
	{
		return $this->_fireEvent($type, $arguments);
	}

	/**
	 * Abstract method to prevent this trait being used by incompatible objects.
	 */
	abstract protected function _fireEvent($type, array $arguments);
}
