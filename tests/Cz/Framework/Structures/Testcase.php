<?php
namespace Cz\Framework\Structures;
use Cz\PHPUnit;

/**
 * Testcase
 * 
 * @package    Framework
 * @category   Structures
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
abstract class Testcase extends PHPUnit\Testcase
{
	/**
	 * @reutrn  FiniteStateFactory
	 */
	protected function _getFactory()
	{
		return new FiniteStateFactory;
	}

	/**
	 * Sample FSM for many of the tests.
	 */
	protected function _getSampleDefinition()
	{
		return array(
			1 => array(2, 3, 4),
			2 => array(2, 3, 4),
			3 => array(4),
			4 => array(),
		);
	}
}
