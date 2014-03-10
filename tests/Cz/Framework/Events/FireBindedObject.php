<?php
namespace Cz\Framework\Events;

/**
 * FireBindedObject
 * 
 * @package    Framework
 * @category   Events
 * @author     Korney Czukowski
 * @copyright  (c) 2014 Korney Czukowski
 * @license    MIT License
 */
class FireBindedObject extends TestEventHandlers
{
	use Events, FireBinded;
}
