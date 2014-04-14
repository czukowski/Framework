<?php
namespace Cz\Framework\Callbacks;
use Cz\PHPUnit;

/**
 * Testcase
 * 
 * This test is common to several specific Callback tests. It verifies that exception
 * is thrown when invalid arguments are passed to Callback constructors.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  CallbackInterface  $object
 */
abstract class Testcase extends PHPUnit\Testcase
{
	/**
	 * Tests Callback type constructor, using test cases from the type-specific test classes.
	 * 
	 * @dataProvider  provideConstruct
	 */
	public function testConstruct($callback, $arguments, $expected)
	{
		$this->setExpectedExceptionFromArgument($expected);
		$this->setupObject(array(
			'arguments' => $arguments === NULL ? array($callback) : array($callback, $arguments),
		));
	}

	/**
	 * Callback type specific test classes implement this data provider.
	 */
	abstract function provideConstruct();
}
