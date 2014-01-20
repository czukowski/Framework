<?php
/**
 * Base testcase class for PHPUnit
 * 
 * @package    Framework
 * @category   PHPUnit
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
namespace Cz\PHPUnit;

abstract class Testcase extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var  object  Tested object instance
	 */
	protected $object;

	protected function setExpectedExceptionFromArgument($expected)
	{
		if ($expected instanceof \Exception)
		{
			$this->setExpectedException(get_class($expected));
		}
	}

	protected function setupObject($options = array())
	{
		$options = $this->getSetupOptions($options);
		$class = $this->getClassReflection($options['classname']);
		$this->object = $class->newInstanceArgs($options['arguments']);
	}

	protected function setupMock($options = array())
	{
		$this->object = $this->createMock($options);
	}

	protected function createMock($options = array())
	{
		$options = $this->getSetupOptions($options);
		return $this->getMock($options['classname'], $options['methods'], $options['arguments'], $options['mock_classname']);
	}

	private function getSetupOptions($options = array())
	{
		$options['classname'] = isset($options['classname']) ? $options['classname'] : $this->getClassName();
		$options['methods'] = isset($options['methods']) ? $options['methods'] : $this->getClassAbstractMethods($options['classname']);
		$options['arguments'] = isset($options['arguments']) ? $options['arguments'] : $this->getClassConstructorArguments();
		$options['mock_classname'] = isset($options['mock_classname']) ? $options['mock_classname'] : '';
		return $options;
	}

	protected function getClassAbstractMethods($classname)
	{
		$methods = array();
		foreach ($this->getClassReflection($classname)->getMethods(\ReflectionMethod::IS_ABSTRACT) as $method)
		{
			$methods[] = $method->getName();
		}
		return $methods;
	}

	protected function getClassConstructorArguments()
	{
		// No arguments by default
		return array();
	}

	protected function getClassName()
	{
		return preg_replace('#Test$#', '', get_class($this));
	}

	protected function getClassReflection($classname)
	{
		return new \ReflectionClass($classname);
	}

	protected function getObjectMethod($object, $name)
	{
		$method = new \ReflectionMethod($object, $name);
		$method->setAccessible(TRUE);
		return $method;
	}

	protected function getObjectProperty($object, $name)
	{
		$property = new \ReflectionProperty($object, $name);
		$property->setAccessible(TRUE);
		return $property;
	}
}