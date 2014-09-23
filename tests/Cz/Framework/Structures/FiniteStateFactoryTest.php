<?php
namespace Cz\Framework\Structures;
use Cz\Framework\Exceptions;

/**
 * FiniteStateFactoryTest
 * 
 * @package    Framework
 * @category   Structures
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  FiniteStateFactory  $object
 */
class FiniteStateFactoryTest extends Testcase
{
	/**
	 * @dataProvider  provideSetup
	 */
	public function testCreateFSM($states, $begin, $end, $expectedStates, $expectedBegin, $expectedEnd)
	{
		$fsm = $this->object->createFSM($states, $begin, $end);
		$this->assertSame($expectedStates, $fsm->getStates());
		$this->assertSame($expectedBegin, $fsm->getBeginStates());
		$this->assertSame($expectedEnd, $fsm->getEndStates());
	}

	/**
	 * @dataProvider  provideSetup
	 */
	public function testSetupFSM($states, $begin, $end, $expectedStates, $expectedBegin, $expectedEnd)
	{
		$fsm = new FiniteState;
		$this->object->setupFSM($fsm, $states, $begin, $end);
		$this->assertSame($expectedStates, $fsm->getStates());
		$this->assertSame($expectedBegin, $fsm->getBeginStates());
		$this->assertSame($expectedEnd, $fsm->getEndStates());
	}

	public function provideSetup()
	{
		$states = $this->_getSampleDefinition();
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
	 * @dataProvider  provideSetupException
	 */
	public function testSetupException($states, $begin, $end, $exceptionMessage)
	{
		$initialStates = array('initial' => array());
		$fsm = $this->object->createFSM($initialStates);
		try
		{
			$this->object->setupFSM($fsm, $states, $begin, $end);
		}
		catch (Exceptions\InvalidArgumentException $e)
		{
			$this->assertInstanceOf('Cz\Framework\Exceptions\InvalidArgumentException', $e);
			$this->assertSame($exceptionMessage, $e->getMessage());
			$actualStates = $this->getObjectProperty($fsm, '_states')
				->getValue($fsm);
			$this->assertSame($initialStates, $actualStates);
		}
	}

	public function provideSetupException()
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
				'Unknown state "4".',
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
}
