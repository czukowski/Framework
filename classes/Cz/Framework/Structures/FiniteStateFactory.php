<?php
namespace Cz\Framework\Structures;
use Cz\Framework\Exceptions;

/**
 * FiniteStateFactory
 * 
 * Factory class for creating Finite State Machines.
 * 
 * @package    Framework
 * @category   Structures
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class FiniteStateFactory
{
	/**
	 * @param   array       $states  All machine states definition
	 * @param   array|NULL  $begin   Valid beginning states (NULL = autodetect)
	 * @param   array|NULL  $end     Valid ending states (NULL = autodetect)
	 * @return  FiniteState
	 */
	public function createFSM($states = array(), $begin = NULL, $end = NULL)
	{
		$fsm = new FiniteState;
		return $this->setupFSM($fsm, $states, $begin, $end);
	}

	/**
	 * @param   FiniteState  $fsm     FSM object instance.
	 * @param   array        $states  All machine states definition
	 * @param   array|NULL   $begin   Valid beginning states (NULL = autodetect)
	 * @param   array|NULL   $end     Valid ending states (NULL = autodetect)
	 * @return  FiniteState
	 */
	public function setupFSM(FiniteState &$fsm, $states = array(), $begin = NULL, $end = NULL)
	{
		$editedFsm = clone $fsm;
		$this->_addStates($editedFsm, $states);
		$this->_setBorderStates($editedFsm, $begin, TRUE);
		$this->_setBorderStates($editedFsm, $end, FALSE);
		$fsm = $editedFsm;
		return $editedFsm;
	}

	/**
	 * @param   FiniteState  $fsm
	 * @param   array        $states
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function _addStates(FiniteState $fsm, $states)
	{
		if ( ! is_array($states))
		{
			throw new Exceptions\InvalidArgumentException('States definition must be array.');
		}
		foreach (array_keys($states) as $state)
		{
			$fsm->addState($state);
		}
		foreach ($states as $state => $nextStates)
		{
			foreach ($nextStates as $nextState)
			{
				$fsm->addTransition($state, $nextState);
			}
		}
	}

	/**
	 * @param   FiniteState  $fsm
	 * @param   array|NULL   $states  States to set as begin or end states.
	 * @param   boolean      $begin   TRUE = `$states` argument are begin states, else end states.
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function _setBorderStates(FiniteState $fsm, $states, $begin)
	{
		if ($states !== NULL && ! is_array($states))
		{
			throw new Exceptions\InvalidArgumentException(($begin ? 'Begin' : 'End').' states definition must be array or NULL for auto-detection.');
		}
		elseif (is_array($states) && ! $this->_isSubset($states, $fsm->getStates()))
		{
			throw new Exceptions\InvalidArgumentException('Invalid state found in '.($begin ? 'begin' : 'end').' states.');
		}
		elseif ($states === NULL)
		{
			$states = $this->_detectBorderStates($fsm, $begin);
		}
		$methodName = ($begin ? 'setBeginStates' : 'setEndStates');
		$fsm->$methodName($states);
	}

	/**
	 * Detects states that no other state linkes to or from.
	 * 
	 * @param  FiniteState  $fsm
	 * @param  boolean      $begin
	 */
	private function _detectBorderStates(FiniteState $fsm, $begin)
	{
		$states = $fsm->getStates();
		$counter = array_combine($states, array_fill(0, count($states), 0));
		foreach ($states as $from)
		{
			foreach ($fsm->getTransitionsFrom($from) as $to)
			{
				$counter[$begin ? $to : $from]++;
			}
		}
		$borderStates = array();
		foreach ($counter as $state => $linksCount)
		{
			if ($linksCount === 0)
			{
				$borderStates[] = $state;
			}
		}
		return $borderStates;
	}

	/**
	 * Validates the first argument is a subset of the second argument.
	 * 
	 * @param   array  $subset
	 * @param   array  $fullSet
	 * @throws  Exceptions\InvalidArgumentException
	 * @deprecated
	 */
	private function _isSubset($subset, $fullSet)
	{
		return ! array_diff($subset, $fullSet);
	}
}
