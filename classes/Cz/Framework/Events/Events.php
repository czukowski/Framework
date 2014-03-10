<?php
namespace Cz\Framework\Events;
use Cz\Framework\Callbacks,
	Cz\Framework\Exceptions;

/**
 * Event
 * 
 * Used to provide event listening and firing methods.
 * 
 * @package    Framework
 * @category   Events
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
trait Events
{
	/**
	 * @var  array  Event handlers container.
	 */
	private $_events = array();

	/**
	 * Adds event handler to the object.
	 * 
	 * @param   string    $type     Event type; can be any string that can be a key of an array
	 * @param   callable  $handler  Event handler; any callable type of instance of Callback
	 * @return  $this
	 */
	public function addEvent($type, $handler)
	{
		$this->_validateEventHandler($handler);
		if ( ! isset($this->_events[$type]))
		{
			$this->_events[$type] = array();
		}
		$this->_events[$type][] = $handler;
		return $this;
	}

	/**
	 * Adds multiple event handlers to the object.
	 * 
	 * @param   array|\Traversable  $events  Key-value pairs of event types and handlers.
	 * @return  $this
	 */
	public function addEvents($events)
	{
		$this->_validateEventsArray($events);
		foreach ($events as $type => $handler)
		{
			$this->addEvent($type, $handler);
		}
		return $this;
	}

	/**
	 * Removes a specific event handler. Ignores non-existing event types or handlers.
	 * 
	 * @param   string    $type     Event type
	 * @param   callable  $handler  Event handler
	 * @return  $this
	 */
	public function removeEvent($type, $handler)
	{
		$this->_validateEventHandler($handler);
		if (isset($this->_events[$type]))
		{
			foreach ($this->_events[$type] as $i => $trackingHandler)
			{
				if ($handler === $trackingHandler)
				{
					unset($this->_events[$type][$i]);
				}
			}
		}
		return $this;
	}

	/**
	 * Removes multiple events at once. Also acts as an alias for `removeAllEvents()` method.
	 * 
	 * @param   array|\Traversable|string|NULL  $events  Event handlers to remove.
	 * @return  $this
	 */
	public function removeEvents($events = NULL)
	{
		if ($events === NULL || is_string($events))
		{
			return $this->removeAllEvents($events);
		}
		$this->_validateEventsArray($events);
		foreach ($events as $type => $handler)
		{
			$this->removeEvent($type, $handler);
		}
		return $this;
	}

	/**
	 * Removes all events of a specific type or events of all types.
	 * 
	 * @param   string|NULL  $type  Type of the event to clear. If `NULL`, clears all events
	 */
	public function removeAllEvents($type = NULL)
	{
		if (is_string($type))
		{
			$this->_events[$type] = array();
		}
		elseif ($type === NULL)
		{
			$this->_events = array();
		}
		else
		{
			throw new Exceptions\InvalidArgumentException('Invalid event type, must be string or NULL.');
		}
		return $this;
	}

	/**
	 * Checks that the event handler is actually callable. Also extracts callback from this
	 * framework's Callback type (modifies the input value).
	 * 
	 * @param   callback|Callbacks\Callback  $handler
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function _validateEventHandler(&$handler)
	{
		if ($handler instanceof Callbacks\Callback)
		{
			$handler = $handler->getCallback();
		}
		if ( ! is_callable($handler))
		{
			throw new Exceptions\InvalidArgumentException('Invalid event handler, must be callable.');
		}
	}

	/**
	 * Checks the events array is actually an array or a Traversable object.
	 * 
	 * @param   array|\Traversable  $events
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function _validateEventsArray($events)
	{
		if ( ! is_array($events) && ! $events instanceof \Traversable)
		{
			throw new Exceptions\InvalidArgumentException('Expected array of event handlers.');
		}
	}

	/**
	 * Fires event of a specific type with specified arguments.
	 * 
	 * @param   string  $type       Event type to fire.
	 * @param   array   $arguments  Arguments to send to event handlers.
	 * @return  $this
	 */
	public function fireEvent($type, array $arguments)
	{
		if (isset($this->_events[$type]))
		{
			foreach ($this->_events[$type] as $callback)
			{
				call_user_func_array($callback, $arguments);
			}
		}
		return $this;
	}

	/**
	 * Fires event as normal, but adds `$this` reference to arguments list.
	 * 
	 * @param   string  $type       Event type to fire.
	 * @param   array   $arguments  Arguments to send to event handlers.
	 * @return  $this
	 */
	public function fireEventBinded($type, array $arguments)
	{
		$arguments[] = $this;
		return $this->fireEvent($type, $arguments);
	}
}
