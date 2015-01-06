<?php
namespace Cz\Framework\Objects;
use Cz\PHPUnit;

/**
 * Testcase
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
abstract class Testcase extends PHPUnit\Testcase
{
	/**
	 * @var  CamelCaseFormatObject  Helper object to format custom access method names.
	 */
	private $format;

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
	 * Used by `ObjectTest::testSetOneArgument()`.
	 */
	public function provideExtendedItems()
	{
		$data = $this->provideItems();
		$data[] = array(new \ArrayObject(array('boo' => 'hoo')));
		return $data;
	}

	/**
	 * Setup unmocked fixture for tests invoking generic access methods (get, set, has, erase).
	 */
	public function setUp()
	{
		$this->setupFormatObject();
		$this->setupObject();
	}

	/**
	 * Setup helper object to format custom access method names.
	 */
	protected function setupFormatObject()
	{
		$this->format = new CamelCaseFormatObject;
	}

	/**
	 * Call original object method to determine custom method name.
	 */
	public function getCustomMethodName($prefix, $name)
	{
		return $this->format->getMethodName($prefix, $name);
	}
}
