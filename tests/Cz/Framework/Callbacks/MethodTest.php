<?php
namespace Cz\Framework\Callbacks;

/**
 * MethodTest
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
 * @property  Method  $object
 */
class MethodTest extends Testcase
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
			array(array($this, 'callbackReturnSelf'), array(), 'Cz\Framework\Callbacks\MethodTest'),
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
		return array(
			// Invalid definitions.
			array(3.14, TRUE),
			array(NULL, TRUE),
			// String definitions.
			array('count', FALSE),
			array('PHPUnit_Framework_TestCase::any', FALSE),
			array('this_function_does_not_exists_hopefully', TRUE),
			array('ThisClassDoesNotExists::this_function_does_not_exists', TRUE),
			array('Cz\Framework\Callbacks\MethodTest', TRUE),
			array('Cz\Framework\Callbacks\ThisClassDoesNotExists', TRUE),
			// Array definitions.
			array(array(), TRUE),
			array(array('single argument'), TRUE),
			array(array(NULL, 'parameter'), TRUE),
			array(array(TRUE, 'parameter'), TRUE),
			array(array(FALSE, 'parameter'), TRUE),
			array(array(3.14, 'parameter'), TRUE),
			array(array('string', 'parameter'), TRUE),
			array(array($this, TRUE), TRUE),
			array(array($this, FALSE), TRUE),
			array(array($this, NULL), TRUE),
			array(array($this, 5.16), TRUE),
			array(array(new \stdClass, 'stdClassDoesNotHaveMethods'), TRUE),
			array(array($this, 'provideConstruct'), FALSE),
			// Object definitions.
			array(new \stdClass, TRUE),
			array($this, TRUE),
			array($this->getMock('Cz\Entities\EntityInterface'), TRUE),
			array(function() {return TRUE;}, FALSE),
		);
	}
}
