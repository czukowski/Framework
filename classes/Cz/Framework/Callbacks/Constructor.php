<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions;

/**
 * Constructor
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  string  $callback  Classname to create an object from.
 */
class Constructor extends Callback
{
	/**
	 * @param  string  $classname
	 * @param  array   $arguments
	 */
	public function __construct($classname, array $arguments = array())
	{
		$this->validateCallback($classname);
		$this->callback = $classname;
		$this->setArguments($arguments);
	}

	/**
	 * @param   string  $callback
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function validateCallback($callback)
	{
		if (is_string($callback))
		{
			if ( ! class_exists($callback))
			{
				throw new Exceptions\InvalidArgumentException('String "'.$callback.'" does not refer to a classname.');
			}
			return;
		}
		throw new Exceptions\InvalidArgumentException('Invalid callback type.');
	}

	/**
	 * @return  object
	 */
	public function invoke()
	{
		// FIXME: implement constructor arguments.
		return new $this->callback;
	}
}
