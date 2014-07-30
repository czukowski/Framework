<?php
namespace Cz\Framework\Structures;
use Cz\PHPUnit,
	Cz\Framework\Exceptions;

/**
 * FiniteStateTest
 * 
 * @package    Framework
 * @category   Structures
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  FiniteState  $object
 */
class FiniteStateTest extends PHPUnit\Testcase
{
	/**
	 * Test `canSwitchState` method, including exceptions on invalid arguments.
	 * 
	 * @dataProvider  provideCanSwitchState
	 */
	public function testCanSwitchState($states, $current, $switchTo, $switchFrom, $expected)
	{
		$this->setupFSM($states, $current, $expected);
		$actual = $this->object->canSwitchState($switchTo, $switchFrom);
		$this->assertSame($expected, $actual);
	}

	public function provideCanSwitchState()
	{
		$states = $this->getSampleDefinition();
		// [all states, current state, switch to state, switch from state, expected result]
		return array(
			// Current: 1, switch 1 -> 2.
			array($states, 1, 2, 1, TRUE),
			// Current: 1, switch current -> 3.
			array($states, 1, 3, NULL, TRUE),
			// Current: 1, switch 3 -> 4.
			array($states, 1, 4, 3, TRUE),
			// Current: 3, switch 2 -> 3.
			array($states, 3, 3, 2, TRUE),
			// Current: 3, switch 2 -> 1.
			array($states, 3, 1, 2, FALSE),
			// Current: none, switch current -> 1.
			array($states, NULL, 1, NULL, new InvalidStateException),
			// Current: none, switch 2 -> 3.
			array($states, NULL, 3, 2, TRUE),
			// Current: invalid, switch 2 -> 3.
			array($states, 5, 3, 2, new Exceptions\InvalidArgumentException),
			// Current: 1, switch 2 -> invalid.
			array($states, 1, 5, 2, new Exceptions\InvalidArgumentException),
			// Current: 2, switch invalid -> 3.
			array($states, 1, 3, 5, new Exceptions\InvalidArgumentException),
			// Current: 3, switch invalid -> invalid.
			array($states, 1, 6, 5, new Exceptions\InvalidArgumentException),
		);
	}

	/**
	 * Test `getBeginStates` method, both graceful and otherwise.
	 * 
	 * @dataProvider  provideGetBorderStates
	 */
	public function testGetBeginStates($states, $graceful, $expected)
	{
		$this->setupFSM($states, NULL, $expected);
		$actual = $this->object->getBeginStates($graceful);
		$this->assertSame($expected, $actual);
	}

	/**
	 * Test `getEndStates` method, both graceful and otherwise.
	 * 
	 * @dataProvider  provideGetBorderStates
	 */
	public function testGetEndStates($states, $graceful, $null, $expected)
	{
		$this->setupFSM($states, NULL, $expected);
		$actual = $this->object->getEndStates($graceful);
		$this->assertSame($expected, $actual);
	}

	public function provideGetBorderStates()
	{
		$states = $this->getSampleDefinition();
		// [all states, graceful request, expected begin states]
		return array(
			// Get border states from defined FSM, regardless of graceful option.
			array($states, FALSE, array(1), array(4)),
			array($states, TRUE, array(1), array(4)),
			// Get border states from undefined FSM, gracefully.
			array(NULL, TRUE, array(), array()),
			// Get border states from undefined FSM, with exception.
			array(NULL, FALSE, new InvalidStateException, new InvalidStateException),
		);
	}

	/**
	 * Test `getCurrentState` method, both graceful and otherwise.
	 * 
	 * @dataProvider  provideGetCurrentState
	 */
	public function testGetCurrentState($states, $current, $graceful, $expected)
	{
		$this->setupFSM($states, $current, $expected);
		$actual = $this->object->getCurrentState($graceful);
		$this->assertSame($expected, $actual);
	}

	public function provideGetCurrentState()
	{
		$states = $this->getSampleDefinition();
		// [all states, set current state, graceful request, expected result]
		return array(
			// Get current state from initialized FSM, regardless of graceful option.
			array($states, 1, TRUE, 1),
			array($states, 2, FALSE, 2),
			array($states, 3, TRUE, 3),
			array($states, 4, FALSE, 4),
			// Get current state from uninitialized FSM, gracefully.
			array($states, NULL, TRUE, NULL),
			// Get current state from uninitialized FSM, with exception.
			array($states, NULL, FALSE, new InvalidStateException),
		);
	}

	/**
	 * Test `setCurrentState` method, including exceptions on invalid arguments.
	 * 
	 * @dataProvider  provideSetCurrentState
	 */
	public function testSetCurrentState($states, $current, $expected)
	{
		$this->setupFSM($states, NULL, $expected);
		$return = $this->object->setCurrentState($current);
		$this->assertSame($this->object, $return);
		$actual = $this->getObjectCurrentState();
		$this->assertSame($expected, $actual);
	}

	public function provideSetCurrentState()
	{
		$states = $this->getSampleDefinition();
		// [all states, set current state, expected result]
		return array(
			// Set valid current states.
			array($states, 1, 1),
			array($states, 2, 2),
			array($states, 3, 3),
			array($states, 4, 4),
			// Set invalid current states.
			array($states, 5, new Exceptions\InvalidArgumentException),
			array($states, NULL, new Exceptions\InvalidArgumentException),
		);
	}

	/**
	 * @dataProvider  provideSwitchState
	 */
	public function testSwitchState($states, $current, $switchTo, $expected)
	{
		$this->setupFSM($states, $current, $expected);
		$return = $this->object->switchState($switchTo);
		$this->assertSame($this->object, $return);
		$actual = $this->getObjectCurrentState();
		$this->assertSame($expected, $actual);
	}

	public function provideSwitchState()
	{
		$states = $this->getSampleDefinition();
		// [all states, set current state, switch to state, expected result]
		return array(
			// Switch state in uninitialized FSM.
			array($states, NULL, 1, new InvalidStateException),
			// Switch state to invalid next state.
			array($states, 1, 1, new InvalidTransitionException),
			// Switch state to invalid state.
			array($states, 1, 5, new Exceptions\InvalidArgumentException),
			// Switch state to valid next state.
			array($states, 1, 2, 2),
		);
	}

	private function getObjectCurrentState()
	{
		return $this->getObjectProperty($this->object, '_current')
			->getValue($this->object);
	}

	/**
	 * @dataProvider  provideGetStates
	 */
	public function testGetStates($states, $graceful, $expected)
	{
		$this->setupFSM($states, NULL, $expected);
		$actual = $this->object->getStates($graceful);
		$this->assertSame($expected, $actual);
	}

	public function provideGetStates()
	{
		$states = $this->getSampleDefinition();
		// [define states, graceful request, expected get states]
		return array(
			// Retrieving states from defined FSM, regardless of graceful option.
			array($states, TRUE, array_keys($states)),
			array($states, FALSE, array_keys($states)),
			// Retrieving states from undefined FSM gracefully.
			array(NULL, TRUE, array()),
			// Retrieving states from undefined FSM with exception
			array(NULL, FALSE, new InvalidStateException),
		);
	}

	/**
	 * @dataProvider  provideIsDefined
	 */
	public function testIsDefined($states, $expected)
	{
		$this->setupFSM($states, NULL, NULL);
		$actual = $this->object->isDefined();
		$this->assertSame($expected, $actual);
	}

	public function provideIsDefined()
	{
		// [define states, expected is defined]
		return array(
			array($this->getSampleDefinition(), TRUE),
			array(NULL, FALSE),
		);
	}

	/**
	 * Sample FSM for many of the tests.
	 */
	private function getSampleDefinition()
	{
		return array(
			1 => array(2, 3, 4),
			2 => array(2, 3, 4),
			3 => array(4),
			4 => array(),
		);
	}

	/**
	 * @dataProvider  provideDefinition
	 */
	public function testDefinition($states, $begin, $end, $expectedStates, $expectedBegin, $expectedEnd)
	{
		$return = $this->object->setDefinition($states, $begin, $end);
		$this->assertSame($this->object, $return);
		$this->assertSame($expectedStates, $this->object->getStates());
		$this->assertSame($expectedBegin, $this->object->getBeginStates());
		$this->assertSame($expectedEnd, $this->object->getEndStates());
	}

	public function provideDefinition()
	{
		$states = $this->getSampleDefinition();
		// [all states, begin states, end states, expected states, expected begin, expected end]
		return array(
			// Simple FSM, begin & end states defined explicitly.
			array(
				$states,
				array(1),
				array(4),
				array(1, 2, 3, 4),
				array(1),
				array(4),
			),
			// Same FSM as before, begin & end states autodetected.
			array(
				$states,
				NULL,
				NULL,
				array(1, 2, 3, 4),
				array(1),
				array(4),
			),
		);
	}

	/**
	 * @dataProvider  provideDefinitionException
	 */
	public function testDefinitionException($states, $begin, $end, $exceptionMessage)
	{
		$initialStates = array('initial' => array());
		$this->object->setDefinition($initialStates);
		try
		{
			$this->object->setDefinition($states, $begin, $end);
		}
		catch (Exceptions\InvalidArgumentException $e)
		{
			$this->assertInstanceOf('Cz\Framework\Exceptions\InvalidArgumentException', $e);
			$this->assertSame($exceptionMessage, $e->getMessage());
			$actualStates = $this->getObjectProperty($this->object, '_states')
				->getValue($this->object);
			$this->assertSame($initialStates, $actualStates);
		}
	}

	public function provideDefinitionException()
	{
		// [all states, begin states, end states]
		return array(
			// Invalid state definition.
			array(
				'not array',
				NULL,
				NULL,
				'States definition must be array.',
			),
			// Missing state '4' definition.
			array(
				array(
					1 => array(2, 3),
					2 => array(2, 3, 4),
					3 => array(4),
				),
				NULL,
				NULL,
				'Invalid next states found for state "2".',
			),
			// Invalid begin state argument type definition.
			array(
				array(
					1 => array(2, 3),
					2 => array(2, 3, 4),
					3 => array(4),
					4 => array(),
				),
				1,
				NULL,
				'Begin states definition must be array or NULL for auto-detection.',
			),
			// Invalid begin state definition.
			array(
				array(
					1 => array(2, 3),
					2 => array(2, 3, 4),
					3 => array(4),
					4 => array(),
				),
				array(5),
				NULL,
				'Invalid state found in begin states.',
			),
			// Invalid end state definition.
			array(
				array(
					1 => array(2, 3),
					2 => array(2, 3, 4),
					3 => array(4),
					4 => array(),
				),
				array(1),
				4,
				'End states definition must be array or NULL for auto-detection.',
			),
			// Invalid end state definition.
			array(
				array(
					1 => array(2, 3),
					2 => array(2, 3, 4),
					3 => array(4),
					4 => array(),
				),
				array(1),
				array(6),
				'Invalid state found in end states.',
			),
		);
	}

	public function setUp()
	{
		$this->setupObject();
	}

	private function setupFSM($states, $current, $expected)
	{
		$this->setExpectedExceptionFromArgument($expected);
		if ($states)
		{
			$this->object->setDefinition($states);
		}
		if ($current)
		{
			$this->object->setCurrentState($current);
		}
	}
}
