<?php
namespace Cz\Framework\Events;
use Cz\PHPUnit;

/**
 * Testcase
 * 
 * @package    Framework
 * @category   Events
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class Testcase extends PHPUnit\Testcase
{
	/**
	 * Retrieves object event handlers.
	 * 
	 * @return  array
	 */
	protected function getObjectEvents()
	{
		return $this->getObjectProperty($this->object, '_events')
			->getValue($this->object);
	}

	/**
	 * Sets object event handlers.
	 * 
	 * @param  array  $events
	 */
	protected function setObjectEvents(array $events)
	{
		$this->getObjectProperty($this->object, '_events')
			->setValue($this->object, $events);
	}

	/**
	 * Create sample callbacks for test event handlers.
	 * 
	 * @return  array
	 */
	protected function createCallbacks()
	{
		$this->setupObject();
		return array(
			array($this->object, 'eventHandler1'),
			array($this->object, 'eventHandler2'),
			$this->object,
		);
	}

	public function setUp()
	{
		$this->setupObject();
	}
}
