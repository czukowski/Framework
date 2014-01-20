<?php
namespace Cz\Framework\Object;

/**
 * MethodAccessObjectTest
 * 
 * @package    Framework
 * @category   Object
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class MethodAccessObjectTest extends Testcase
{
	/**
	 * Tests array access methods from `MethodAccess` trait by asserting key not
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
			$this->assertFalse($this->object->{$this->getCustomMethodName('exists', $key)}());
			$this->object->{$this->getCustomMethodName('set', $key)}($value);
			$this->assertSame($value, $this->object->{$this->getCustomMethodName('get', $key)}($value));
			$this->assertTrue($this->object->{$this->getCustomMethodName('exists', $key)}());
			$this->object->{$this->getCustomMethodName('erase', $key)}();
			$this->assertFalse($this->object->{$this->getCustomMethodName('exists', $key)}());
		}
	}

	/**
	 * Test invalid setter method call. Should end with exception.
	 * 
	 * @test
	 * @expectedException         Cz\Framework\Exceptions\InvalidArgumentException
	 * @expectedExceptionMessage  Cannot call without argument: setSometing
	 */
	public function testInvalidSetCall()
	{
		$this->object->setSometing();
	}

	/**
	 * Test undefined method call. Should end with exception.
	 * 
	 * @test
	 * @expectedException         Cz\Framework\Exceptions\InvalidArgumentException
	 * @expectedExceptionMessage  Call to undefined method: accessSomeValue
	 */
	public function testCallUndefinedMethod()
	{
		$this->object->accessSomeValue();
	}
}
