<?php
namespace Cz\Framework\Structures;
use Cz\Framework\Exceptions;

/**
 * FiniteState
 * 
 * Basic Finite State Machine implementation.
 * 
 * @package    Framework
 * @category   Structures
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class FiniteState
{
	/**
	 * @var  array  Valid begin states.
	 */
	private $_begin = array();
	/**
	 * @var  array  Valid end states.
	 */
	private $_end = array();
	/**
	 * @var  mixed  Current state key.
	 */
	private $_current;
	/**
	 * @var  array  FSM definition. Keys are states and values are valid next states.
	 */
	private $_states;

	/**
	 * Get all begin states. May return empty array or throw exception when called
	 * while the FSM hasn't been defined yet (ie states not set by prior `setDefinition` call).
	 * 
	 * @param   boolean  $graceful  If TRUE, return empty value when FSM hasn't been initialized.
	 * @return  array
	 */
	public function getBeginStates($graceful = FALSE)
	{
		$this->_validateDefined($graceful);
		return $this->_begin;
	}

	/**
	 * Returns the current state. May return NULL or throw exception when called while the FSM
	 * hasn't been initialized yet (ie is not in any state).
	 * 
	 * @param   boolean  $graceful  If TRUE, return NULL when FSM hasn't been initialized.
	 * @return  mixed
	 * @throws  InvalidStateException
	 */
	public function getCurrentState($graceful = FALSE)
	{
		$this->_validateDefined($graceful);
		if (isset($this->_current))
		{
			return $this->_current;
		}
		elseif ($graceful)
		{
			return;
		}
		throw new InvalidStateException('Current state not initialized.');
	}

	/**
	 * Returns all defined end states. May return empty array or throw exception when called
	 * while the FSM hasn't been defined yet (ie states not set by prior `setDefinition` call).
	 * 
	 * @param   boolean  $graceful  If TRUE, return empty value when FSM hasn't been initialized.
	 * @return  array
	 */
	public function getEndStates($graceful = FALSE)
	{
		$this->_validateDefined($graceful);
		return $this->_end;
	}

	/**
	 * Get all defined machine states. May return empty array or throw exception when called
	 * while the FSM hasn't been defined yet (ie states not set by prior `setDefinition` call).
	 * 
	 * @param   boolean  $graceful  If TRUE, return empty value when FSM hasn't been initialized.
	 * @return  array
	 */
	public function getStates($graceful = FALSE)
	{
		$this->_validateDefined($graceful);
		return isset($this->_states)
			? array_keys($this->_states)
			: array();
	}

	/**
	 * @param   boolean  $graceful
	 * @throws  InvalidStateException
	 */
	private function _validateDefined($graceful)
	{
		if ( ! isset($this->_states) && ! $graceful)
		{
			throw new InvalidStateException('States not defined.');
		}
	}

	/**
	 * @param   array       $states  All machine states definition
	 * @param   array|NULL  $begin   Valid beginning states (NULL = autodetect)
	 * @param   array|NULL  $end     Valid ending states (NULL = autodetect)
	 * @return  $this
	 */
	public function setDefinition($states = array(), $begin = NULL, $end = NULL)
	{
		$this->_setStates($states);
		$this->_setBorderStates($begin, TRUE);
		$this->_setBorderStates($end, FALSE);
		return $this;
	}

	/**
	 * @param   array  $states
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function _setStates($states = array())
	{
		if ( ! is_array($states))
		{
			throw new Exceptions\InvalidArgumentException('States definition must be array.');
		}
		$allStates = array_keys($states);
		foreach ($states as $state => $nextStates)
		{
			if ( ! $this->_isSubset($nextStates, $allStates))
			{
				throw new Exceptions\InvalidArgumentException('Invalid next states found for state "'.$state.'".');
			}
		}
		$this->_states = $states;
	}

	/**
	 * @param   array|NULL  $states  States to set as begin or end states.
	 * @param   boolean     $begin   TRUE = 1st argument are begin states, else end states.
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function _setBorderStates($states, $begin)
	{
		if ($states !== NULL && ! is_array($states))
		{
			throw new Exceptions\InvalidArgumentException(($begin ? 'Begin' : 'End').' states definition must be array or NULL for auto-detection.');
		}
		elseif (is_array($states) && ! $this->_isSubset($states, $this->getStates()))
		{
			throw new Exceptions\InvalidArgumentException('Invalid state found in '.($begin ? 'begin' : 'end').' states.');
		}
		elseif ($states === NULL)
		{
			$states = $this->_detectBorderStates($begin);
		}
		$this->{($begin ? '_begin' : '_end')} = $states;
	}

	/**
	 * Detects states that no other state linkes to or from.
	 * 
	 * @param  boolean  $begin
	 */
	private function _detectBorderStates($begin)
	{
		$allStates = $this->getStates();
		$counter = array_combine($allStates, array_fill(0, count($allStates), 0));
		foreach ($this->_states as $from => $nextStates)
		{
			foreach ($nextStates as $to)
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
	 */
	private function _isSubset($subset, $fullSet)
	{
		return ! array_diff($subset, $fullSet);
	}

	/**
	 * Sets FSM to any valid state.
	 * 
	 * @param   mixed  $state
	 * @return  $this
	 */
	public function setCurrentState($state)
	{
		$this->_validateState($state);
		$this->_current = $state;
		return $this;
	}

	/**
	 * Switches FSM from the current state to the valid next state. If impossible to switch
	 * to the requested state, an exception is thrown.
	 * 
	 * @param   mixed  $state  State to switch to.
	 * @return  $this
	 * @throws  Exceptions\InvalidArgumentException
	 */
	public function switchState($state)
	{
		if ( ! $this->canSwitchState($state))
		{
			throw new InvalidTransitionException('Cannot switch from "'.$this->_current.'" to "'.$state.'".');
		}
		$this->_current = $state;
		return $this;
	}

	/**
	 * Tells whether the FSM can switch to a requested state from its current state,
	 * or from another state specified in the 2nd argument.
	 * 
	 * @param   mixed  $state  State to switch to.
	 * @param   mixed  $from   State to switch from (current state if NULL).
	 * @return  boolean
	 */
	public function canSwitchState($state, $from = NULL)
	{
		$this->_validateState($state);
		if ($from === NULL)
		{
			$from = $this->getCurrentState();
		}
		else
		{
			$this->_validateState($from);
		}
		return in_array($state, $this->_states[$from]);
	}

	/**
	 * Validates argument to be a defined FSM state.
	 * 
	 * @param   mixed  $state
	 * @throws  Exceptions\InvalidArgumentException
	 */
	private function _validateState($state)
	{
		if ( ! array_key_exists($state, $this->_states))
		{
			throw new Exceptions\InvalidArgumentException('Invalid state "'.$state.'" requested.');
		}
	}
}
