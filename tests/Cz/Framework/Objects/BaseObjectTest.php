<?php
namespace Cz\Framework\Objects;
use Cz\Framework\Exceptions;

/**
 * ObjectTest
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  BaseObject  $object
 */
class BaseObjectTest extends Testcase
{
	/**
	 * Tests generic access methods by asserting key not exists at first, then
	 * setting the key and value and asserting it now exists and the gotten value
	 * is same as the one set, then eventually deleting the key and asserting it
	 * does not exist anymore.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testAccess($items)
	{
		foreach ($items as $key => $value)
		{
			$this->assertFalse($this->object->exists($key));
			$this->object->set($key, $value);
			$this->assertSame($value, $this->object->get($key));
			$this->assertTrue($this->object->exists($key));
			$this->object->erase($key);
			$this->assertFalse($this->object->exists($key));
		}
	}

	/**
	 * Test generic getter by calling it without the default value parameter, but
	 * not setting the value first. Expecting exception, because value was not set
	 * and the default value was not passed. Note that the exception will fire and
	 * test finishes right after getting the first item, effectively skipping all the
	 * other items in the array from the data provider, but that doesn't get in the
	 * way of what we're intending to test here.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testGetUnsetOneArgument($items)
	{
		$this->setExpectedException(get_class(new Exceptions\InvalidArgumentException));
		foreach (array_keys($items) as $key)
		{
			$this->object->get($key);
		}
	}

	/**
	 * Test generic getter by calling it with the default value parameter, but not
	 * setting the value first. Expecting return of the default value.
	 * 
	 * Note that we don't need to cover the other two similar cases while the value
	 * has been previously set, because that has been verified by `testAccess()`.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testGetUnsetTwoArguments($items)
	{
		foreach (array_keys($items) as $key)
		{
			$actual = $this->object->get($key, 'default value');
			$this->assertSame('default value', $actual);
		}
	}

	/**
	 * Tests generic setter by passing array or Traversable object as the only argument.
	 * Expecting the object to set each key and value from that array or object.
	 * 
	 * @test
	 * @dataProvider  provideExtendedItems
	 */
	public function testSetOneArgument($items)
	{
		$this->object->set($items);
		foreach ($items as $key => $value)
		{
			$this->assertSame($value, $this->object->get($key));
		}
	}

	/**
	 * Tests generic unsetter by calling it without any argument. Expecting the object
	 * is emptied as a result.
	 * 
	 * @test
	 * @dataProvider  provideExtendedItems
	 */
	public function testEraseNoArguments($items)
	{
		$this->object->set($items);
		$this->object->erase();
		$this->assertEmpty($this->object->toArray());
	}

	/**
	 * Tests generic `clear()` method. Same as in the previous test, expecting the object
	 * is emptied as a result.
	 * 
	 * @test
	 * @dataProvider  provideExtendedItems
	 */
	public function testClear($items)
	{
		$this->object->set($items);
		$this->object->clear();
		$this->assertEmpty($this->object->toArray());
	}

	/**
	 * Tests count method.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testCount($items)
	{
		$this->object->set($items);
		$expected = count(array_values($items));
		$this->assertSame($expected, $this->object->count());
	}

	/**
	 * Tests toArray method.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testToArray($items)
	{
		$this->object->set($items);
		$this->assertSame($items, $this->object->toArray());
	}

	/**
	 * Tests custom getter methods, eg `getSomething()`, by mocking the respective getter
	 * methods, according to the items from the data provider, and then calling the generic
	 * getter for that key, both with and without the default values, and checking whether
	 * the correct getter methods were called, including the arguments list.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testCustomGetter($items)
	{
		$this->setupCustomMethods('get', $items);
		$expected = array();
		foreach (array_keys($items) as $key)
		{
			$this->object->get($key);
			$expected[] = array($this->getCustomMethodName('callbackget', $key), array());
			$this->object->get($key, 'default value');
			$expected[] = array($this->getCustomMethodName('callbackget', $key), array('default value'));
		}
		$this->assertSame($expected, $this->callbackArguments);
	}

	/**
	 * Similarly to `testCustomGetter()`, tests custom setter methods, eg `setSomeValue()`, by
	 * calling the generic setter method for the keys and checking whether the correct setter
	 * methods were called including the arguments.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testCustomSetter($items)
	{
		$this->setupCustomMethods('set', $items);
		$expected = array();
		foreach ($items as $key => $value)
		{
			$this->object->set($key, $value);
			$expected[] = array($this->getCustomMethodName('callbackset', $key), array($value));
		}
		$this->assertSame($expected, $this->callbackArguments);
	}

	/**
	 * Similarly to the previous tests, testing the custom "issetter" methods, eg. `existsSomething()`
	 * by calling the generic `exists()` method.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testCustomIssetter($items)
	{
		$this->setupCustomMethods('exists', $items);
		$expected = array();
		foreach (array_keys($items) as $key)
		{
			$this->object->exists($key);
			$expected[] = array($this->getCustomMethodName('callbackexists', $key), array());
		}
		$this->assertSame($expected, $this->callbackArguments);
	}

	/**
	 * Similarly to the previous tests, testing the custom "unsetter" methods, eg. `eraseSomething()`
	 * by calling the generic `erase()` method.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testCustomUnsetter($items)
	{
		$this->setupCustomMethods('erase', $items);
		$expected = array();
		foreach (array_keys($items) as $key)
		{
			$this->object->erase($key);
			$expected[] = array($this->getCustomMethodName('callbackerase', $key), array());
		}
		$this->assertSame($expected, $this->callbackArguments);
	}

	/**
	 * Setup fixture with mocked custom methods, for custom method tests (ie. `getSomething()`,
	 * `setStuff()`, etc) automatically called by the generic access methods.
	 * 
	 * Also mock abstract `_getMethodName()` and `_getAccessParameters()` methods to use the
	 * helper object when called.
	 */
	public function setupCustomMethods($prefix, $items)
	{
		$this->callbackArguments = array();
		$methods = $this->getMockMethods($prefix, $items);
		$this->setupMock(array(
			'methods' => array_merge($methods, array('_getMethodName', '_getAccessParameters')),
		));
		foreach ($methods as $method)
		{
			$this->object->expects($this->any())
				->method($method)
				->will($this->returnCallback(array($this, 'callback'.$method)));
		}
		$this->object->expects($this->any())
			->method('_getMethodName')
			->will($this->returnCallback(array($this, 'getCustomMethodName')));
	}

	/**
	 * Get method names we'll need to mock based on items from data provider.
	 */
	protected function getMockMethods($prefix, $items)
	{
		$methods = array();
		foreach (array_keys($items) as $key)
		{
			$methods[] = $this->getCustomMethodName($prefix, $key);
		}
		return $methods;
	}

	/**
	 * Mock invocation callback trap. Stores information about which custom object methods
	 * were called, incl. the arguments list.
	 */
	public function __call($method, $args)
	{
		$this->callbackArguments[] = array($method, $args);
	}

	/**
	 * Setup mocked base object fixture.
	 */
	public function setUp()
	{
		$this->setupFormatObject();
		$this->setupMock();
	}
}
