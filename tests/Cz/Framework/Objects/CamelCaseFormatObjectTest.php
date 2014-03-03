<?php
namespace Cz\Framework\Objects;

/**
 * CamelCaseFormatObjectTest
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  CamelCaseFormatObject  $object
 */
class CamelCaseFormatObjectTest extends Testcase
{
	/**
	 * Tests method name builder.
	 * 
	 * @dataProvider  provideGetMethodName
	 */
	public function testGetMethodName($type, $name, $expected)
	{
		$actual = $this->object->getMethodName($type, $name);
		$this->assertSame($expected, $actual);
	}

	public function provideGetMethodName()
	{
		return array(
			// Common use cases.
			array('get', 'some', 'getSome'),
			array('get', 0, 'get0'),
			array('set', 1, 'set1'),
			array('exists', 'someValue', 'existsSomeValue'),
			array('other', 'value', 'otherValue'),
			// Method doesn't deal with method name validation and will still return a value.
			array('erase', 'some-string', 'eraseSome-string'),
			array('set', 'another string', 'setAnother string'),
		);
	}

	/**
	 * Tests method name decomposer.
	 * 
	 * @dataProvider  provideGetAccessParameters
	 */
	public function testGetAccessParameters($methodName, $expectedType, $expectedName)
	{
		list ($actualType, $actualName) = $this->object->getAccessParameters($methodName);
		$this->assertSame($expectedType, $actualType);
		$this->assertSame($expectedName, $actualName);
	}

	public function provideGetAccessParameters()
	{
		return array(
			// Common use cases.
			array('getSomething', 'get', 'something'),
			array('setAnother', 'set', 'another'),
			array('existsThat', 'exists', 'that'),
			array('eraseStuff', 'erase', 'stuff'),
			// Method doesn't deal with method name validation and will still return a value,
			// as long as there's a valid access type.
			array('getsome', 'get', 'some'),
			array('getSome Thing', 'get', 'some Thing'),
			array('get Some Thing', 'get', ' Some Thing'),
			// But it'll return NULLs if encountered unknown access type (ie anything but
			// `get`, `set`, `exists` or `erase`).
			array('forgetSome', NULL, NULL),
		);
	}
}
