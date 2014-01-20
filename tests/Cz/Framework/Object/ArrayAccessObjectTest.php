<?php
namespace Cz\Framework\Object;

/**
 * ArrayAccessObjectTest
 * 
 * @package    Framework
 * @category   Object
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  ArrayAccessObject  $object
 */
class ArrayAccessObjectTest extends Testcase
{
	/**
	 * Tests array access methods from `ArrayAccess` trait by asserting key not
	 * exists at first, then setting the key and value and asserting it now exists
	 * and the gotten value is same as the one set, then eventually deleting the
	 * key and asserting it does not exist anymore.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testAccess($items)
	{
		foreach ($items as $key => $value)
		{
			$this->assertFalse(isset($this->object[$key]));
			$this->object[$key] = $value;
			$this->assertSame($value, $this->object[$key]);
			$this->assertTrue(isset($this->object[$key]));
			unset($this->object[$key]);
			$this->assertFalse(isset($this->object[$key]));
		}
	}

	/**
	 * Tests countable implementation.
	 * 
	 * Note: `Countable` interface is not the `ArrayAccess` trait's own implementation,
	 * the original `Object` class contains compatible method, although doesn't declare
	 * interface implementation.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testCount($items)
	{
		$this->object->set($items);
		$expected = count(array_values($items));
		$this->assertSame($expected, count($this->object));
	}
}
