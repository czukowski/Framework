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
	 * @dataProvider  provideConstruct
	 */
	public function testConstruct($callback, $expectException)
	{
		if ($expectException)
		{
			$this->setExpectedException('Cz\Framework\Exceptions\InvalidArgumentException');
		}
		$this->setupObject(array(
			'arguments' => array($callback),
		));
	}

	/**
	 * @return  array
	 */
	abstract function provideConstruct();
}
