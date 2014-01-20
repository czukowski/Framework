<?php
namespace Cz\Framework\Object;
use Cz\PHPUnit;

/**
 * Testcase
 * 
 * @package    Framework
 * @category   Object
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
abstract class Testcase extends PHPUnit\Testcase
{
	/**
	 * Data provider for the most of the tests here.
	 */
	public function provideItems()
	{
		return array(
			array(array(1, 2, 3)),
			array(array('foo' => 'bar', 'boo' => 123)),
			array(array('object' => new \stdClass)),
		);
	}

	/**
	 * Data provider includes the data from the previous provider plus an ArrayObject.
	 */
	public function provideExtendedItems()
	{
		$data = $this->provideItems();
		$data[] = array(new \ArrayObject(array('boo' => 'hoo')));
		return $data;
	}

	/**
	 * Setup unmocked fixture for tests invoking generic access methods (get, set, exists, erase).
	 */
	public function setUp()
	{
		$this->setupObject();
	}

	/**
	 * Call original object method to determine custom method name.
	 */
	protected function getCustomMethodName($prefix, $name)
	{
		return $this->getObjectMethod($this->object, '_getMethodName')
			->invoke($this->object, $prefix, $name);
	}

}
