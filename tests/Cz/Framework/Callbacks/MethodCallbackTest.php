<?php
namespace Cz\Framework\Callbacks;
use Cz\Framework\Exceptions;

/**
 * MethodCallbackTest
 * 
 * Tests the Method Callback invocation return values. If an object is expected as the return
 * value, its type is checked, other return types are compared to the expected return values.
 * 
 * @package    Framework
 * @category   Callbacks
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  MethodCallback  $object
 */
class MethodCallbackTest extends Testcase
{
	/**
	 * @dataProvider  provideInvoke
	 */
	public function testInvoke($callback, $arguments, $expected)
	{
		$this->setupObject(array(
			'arguments' => array($callback, $arguments),
		));
		$actual = $this->object->invoke();
		if (is_object($actual))
		{
			$this->assertInstanceOf($expected, $actual);
		}
		else
		{
			$this->assertSame($expected, $actual);
		}
	}

	public function provideInvoke()
	{
		return array(
			array(array($this, 'callbackReturnNull'), array(), NULL),
			array(array($this, 'callbackReturnSelf'), array(), 'Cz\Framework\Callbacks\MethodCallbackTest'),
			array(array($this, 'callbackReturnArgument'), array(TRUE), TRUE),
			array(array($this, 'callbackReturnArgument'), array(array('foo' => 'bar')), array('foo' => 'bar')),
			array('PHPUnit_Framework_TestCase::setUpBeforeClass', array(), NULL),
			array('PHPUnit_Framework_TestCase::any', array(), 'PHPUnit_Framework_MockObject_Matcher_AnyInvokedCount'),
			array(function() {return TRUE;}, array(), TRUE),
			array(function($arg1, $arg2) {return $arg1 + $arg2;}, array(1, 2), 3),
		);
	}

	public function callbackReturnArgument($argument)
	{
		return $argument;
	}

	public function callbackReturnNull()
	{
		return NULL;
	}

	public function callbackReturnSelf()
	{
		return $this;
	}

	public function provideConstruct()
	{
		// [callback definition, callback arguments, expected exception]
		return array(
			// Invalid definitions.
			array(3.14, NULL, new Exceptions\InvalidArgumentException),
			array(NULL, NULL, new Exceptions\InvalidArgumentException),
			// String definitions.
			array('count', NULL, NULL),
			array('PHPUnit_Framework_TestCase::any', NULL, NULL),
			array('this_function_does_not_exists_hopefully', NULL, new Exceptions\InvalidArgumentException),
			array('ThisClassDoesNotExists::this_function_does_not_exists', NULL, new Exceptions\InvalidArgumentException),
			array('Cz\Framework\Callbacks\MethodTest', NULL, new Exceptions\InvalidArgumentException),
			array('Cz\Framework\Callbacks\ThisClassDoesNotExists', NULL, new Exceptions\InvalidArgumentException),
			// Array definitions.
			array(array(), NULL, new Exceptions\InvalidArgumentException),
			array(array('single argument'), NULL, new Exceptions\InvalidArgumentException),
			array(array(NULL, 'parameter'), NULL, new Exceptions\InvalidArgumentException),
			array(array(TRUE, 'parameter'), NULL, new Exceptions\InvalidArgumentException),
			array(array(FALSE, 'parameter'), NULL, new Exceptions\InvalidArgumentException),
			array(array(3.14, 'parameter'), NULL, new Exceptions\InvalidArgumentException),
			array(array('string', 'parameter'), NULL, new Exceptions\InvalidArgumentException),
			array(array($this, TRUE), NULL, new Exceptions\InvalidArgumentException),
			array(array($this, FALSE), NULL, new Exceptions\InvalidArgumentException),
			array(array($this, NULL), NULL, new Exceptions\InvalidArgumentException),
			array(array($this, 5.16), NULL, new Exceptions\InvalidArgumentException),
			array(array(new \stdClass, 'stdClassDoesNotHaveMethods'), NULL, new Exceptions\InvalidArgumentException),
			array(array($this, 'provideConstruct'), NULL, NULL),
			// Object definitions.
			array(new \stdClass, NULL, new Exceptions\InvalidArgumentException),
			array($this, NULL, new Exceptions\InvalidArgumentException),
			array($this->getMock('Cz\Entities\EntityInterface'), NULL, new Exceptions\InvalidArgumentException),
			array(function() {return TRUE;}, NULL, NULL),
		);
	}
}
