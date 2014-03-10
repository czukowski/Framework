<?php
namespace Cz\Framework\Objects;
use Cz\Framework\Events;

/**
 * EventObject
 * 
 * Base object extension with events support.
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
abstract class EventObject extends BaseObject
{
	use Events\Events, Events\FireBinded;

	/**
	 * Object getter method that fires 'before-get' and 'after-get' events.
	 * Take care not to call object's getter in the event handlers!
	 * 
	 * @param   string  $key
	 * @param   mixed   $default
	 * @return  mixed
	 */
	protected function _get($key, $default = NULL)
	{
		$arguments = array($key, $default);
		$this->fireEvent('before-get', $arguments);
		$value = func_num_args() > 1
			? parent::_get($key, $default)
			: parent::_get($key);
		$this->fireEvent('after-get', $arguments);
		return $value;
	}

	/**
	 * Object getter method that fires 'before-set' and 'after-set' events.
	 * Take care not to call object's setter in the event handlers!
	 * 
	 * @param   string  $key
	 * @param   mixed   $value
	 * @return  $this
	 */
	protected function _set($key, $value)
	{
		$this->fireEvent('before-set', func_get_args());
		parent::_set($key, $value);
		$this->fireEvent('after-set', func_get_args());
		return $this;
	}

	/**
	 * Object getter method that fires 'before-erase' and 'after-erase' events.
	 * Take care not to call object's unsetter in the event handlers!
	 * 
	 * @param   string  $key
	 * @return  $this
	 */
	protected function _erase($key)
	{
		$this->fireEvent('before-erase', func_get_args());
		parent::_exists($key);
		$this->fireEvent('after-erase', func_get_args());
		return $this;
	}

	/**
	 * Object getter method that fires 'before-exists' and 'after-exists' events.
	 * Take care not to call object's issetter in the event handlers!
	 * 
	 * @param   string  $key
	 * @return  boolean
	 */
	protected function _exists($key)
	{
		$this->fireEvent('before-exists', func_get_args());
		$value = parent::_exists($key);
		$this->fireEvent('after-exists', func_get_args());
		return $value;
	}

	
}
