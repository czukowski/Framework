<?php
namespace Cz\Framework\Objects;
use Cz\Codebench\Benchmark;

/**
 * Benchmark for different object access methods.
 * 
 * Note that the benchmarked classes belong under the unit tests dir and therefore not normally
 * available.
 * 
 * @package    Framework
 * @category   Objects
 * @author     Korney Czukowski
 * @copyright  (c) 2015 Korney Czukowski
 * @license    MIT License
 */
class AccessBenchmark extends Benchmark
{
	/**
	 * @var  string  Benchmark description.
	 */
	public $description = 'Benchmark for different object access methods.';
	/**
	 * @var  array  Subjects for the benchmark represent numbers of key-value pairs in the objects
	 *              and key to retrieve.
	 */
	public $subjects = array(
		'small' => array(10, 'key5'),
		'large' => array(10000, 'key5555'),
	);
	/**
	 * @var  array  This will contain generated objects whose performance will be measured.
	 */
	private $_objects = array();
	/**
	 * @var  array  These are classnames for generating test objects.
	 */
	private $_classes = array(
		'all' => 'Cz\Framework\Objects\AllAccessObject',
		'array' => 'Cz\Framework\Objects\ArrayAccessObject',
		'method' => 'Cz\Framework\Objects\MethodAccessObject',
		'property' => 'Cz\Framework\Objects\PropertyAccessObject',
	);

	/**
	 * Pre-generate benchmark subjects in order to not affect benchmarking.
	 */
	public function __construct()
	{
		parent::__construct();
		foreach ($this->_classes as $key => $className)
		{
			foreach ($this->subjects as $params)
			{
				$count = reset($params);
				if ( ! isset($this->_objects[$key]))
				{
					$this->_objects[$key] = array();
				}
				$this->_objects[$key][$count] = $this->_createObjectInstance($className, $count);
			}
		}
	}

	/**
	 * Creates an object of the specified type and fills it with the test data.
	 * 
	 * @param   string   $className
	 * @param   integer  $count
	 * @return  ObjectBase
	 */
	private function _createObjectInstance($className, $count)
	{
		$class = new \ReflectionClass($className);
		$object = $class->newInstance();
		for ($i = 0; $i < $count; $i++)
		{
			$object->set('key'.$i, 'value'.$i);
		}
		return $object;
	}

	/**
	 * Benchmark AllAccessObject using `$object->get($key)` method.
	 */
	public function benchAllAccessObjectDefaultAccessMethod($subject)
	{
		list ($objectKey, $getKey) = $subject;
		return $this->_objects['all'][$objectKey]->get($getKey);
	}

	/**
	 * Benchmark AllAccessObject using `$object[$key]` method.
	 */
	public function benchAllAccessObjectArrayAccessMethod($subject)
	{
		list ($objectKey, $getKey) = $subject;
		return $this->_objects['all'][$objectKey][$getKey];
	}

	/**
	 * Benchmark AllAccessObject using `$object->{$key}` method.
	 */
	public function benchAllAccessObjectPropertyAccessMethod($subject)
	{
		list ($objectKey, $getKey) = $subject;
		return $this->_objects['all'][$objectKey]->{$getKey};
	}

	/**
	 * Benchmark AllAccessObject using `$object->getKey()` method.
	 */
	public function benchAllAccessObjectMethodAccessMethod($subject)
	{
		list ($objectKey, $getKey) = $subject;
		$getMethodName = 'get'.ucfirst($getKey);
		return $this->_objects['all'][$objectKey]->$getMethodName();
	}

	/**
	 * Benchmark ArrayAccessObject using `$object->get($key)` method.
	 */
	public function benchArrayAccessObjectDefaultAccessMethod($subject)
	{
		list ($objectKey, $getKey) = $subject;
		return $this->_objects['array'][$objectKey]->get($getKey);
	}

	/**
	 * Benchmark ArrayAccessObject using `$object[$key]` method.
	 */
	public function benchArrayAccessObjectArrayAccessMethod($subject)
	{
		list ($objectKey, $getKey) = $subject;
		return $this->_objects['array'][$objectKey][$getKey];
	}

	/**
	 * Benchmark MethodAccessObject using `$object->get($key)` method.
	 */
	public function benchMethodAccessObjectDefaultAccessMethod($subject)
	{
		list ($objectKey, $getKey) = $subject;
		return $this->_objects['method'][$objectKey]->get($getKey);
	}

	/**
	 * Benchmark MethodAccessObject using `$object->getKey()` method.
	 */
	public function benchMethodAccessObjectMethodAccessMethod($subject)
	{
		list ($objectKey, $getKey) = $subject;
		$getMethodName = 'get'.ucfirst($getKey);
		return $this->_objects['method'][$objectKey]->$getMethodName();
	}

	/**
	 * Benchmark PropertyAccessObject using `$object->get($key)` method.
	 */
	public function benchPropertyAccessObjectDefaultAccessMethod($subject)
	{
		list ($objectKey, $getKey) = $subject;
		return $this->_objects['property'][$objectKey]->get($getKey);
	}

	/**
	 * Benchmark PropertyAccessObject using `$object->get($key)` method.
	 */
	public function benchPropertyAccessObjectPropertyAccessMethod($subject)
	{
		list ($objectKey, $getKey) = $subject;
		return $this->_objects['property'][$objectKey]->{$getKey};
	}
}
