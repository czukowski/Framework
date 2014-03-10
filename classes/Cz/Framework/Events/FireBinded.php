<?php
namespace Cz\Framework\Events;

/**
 * FireBinded
 * 
 * Basic `fireEvent` implementation with additional parameter referencing the current object.
 * 
 * @package    Framework
 * @category   Events
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
trait FireBinded
{
	/**
	 * Fires event as normal, but adds `$this` reference to arguments list.
	 * 
	 * @param   string  $type       Event type to fire.
	 * @param   array   $arguments  Arguments to send to event handlers.
	 * @return  $this
	 */
	public function fireEvent($type, array $arguments)
	{
		$arguments[] = $this;
		return $this->_fireEvent($type, $arguments);
	}

	/**
	 * Abstract method to prevent this trait being used by incompatible objects.
	 */
	abstract protected function _fireEvent($type, array $arguments);
}
