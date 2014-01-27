<?php
namespace Cz\Framework\Object;
use Cz\Framework;

/**
 * AllAccessObject
 * 
 * @package    Framework
 * @category   Object
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 * 
 * @property  AllAccessObject  $object
 */
class AllAccessObjectTest extends Testcase
{
	/**
	 * Tests array, method and property access methods at once, from all three traits,
	 * by asserting key not exists at first, then setting the key and value and asserting
	 * it now exists and the gotten value is same as the one set, then eventually deleting
	 * the key and asserting it does not exist anymore.
	 * 
	 * @test
	 * @dataProvider  provideItems
	 */
	public function testAccess($items)
	{
		foreach ($items as $key => $value)
		{
			$this->assertFalse(isset($this->object->{$key}));
			$this->object[$key] = $value;
			$this->assertSame($value, $this->object->{$this->getCustomMethodName('get', $key)}());
			$this->assertTrue(isset($this->object[$key]));
			unset($this->object->{$key});
			$this->assertFalse($this->object->{$this->getCustomMethodName('exists', $key)}());
		}
	}
}
