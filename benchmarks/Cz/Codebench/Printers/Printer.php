<?php
namespace Cz\Codebench\Printers;

/**
 * Benchmark results printer base class.
 * 
 * @package    Framework
 * @category   Codebench
 * @author     Korney Czukowski
 * @copyright  (c) 2015 Korney Czukowski
 * @license    MIT License
 * 
 * Using the default codebench template logic from Kohana/Codebench module.
 * 
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license.html
 */
abstract class Printer
{
	/**
	 * @var  array
	 */
	protected $_results;

	/**
	 * @param  array  $results  Codebench results
	 */
	public function __construct(array $results)
	{
		$this->_results = $results;
	}

	/**
	 * Prints Codebench results.
	 */
	abstract public function printResults();

	/**
	 * Print results header, may be reused by other implementations.
	 */
	public function printHeader()
	{
		if (empty($this->_results))
		{
			echo "Library not found\n";
		}
		elseif (empty($this->_results['benchmarks']))
		{
			echo "No methods found to benchmark!\n";
			echo "Remember to prefix the methods you want to benchmark with “bench”.\n";
		}
		else
		{
			echo $this->_results['class']."\n";
			echo str_repeat('-', strlen($this->_results['class']))."\n";
			echo strip_tags($this->_results['description'])."\n";
			echo count($this->_results['benchmarks'])." method(s), "
				.count($this->_results['subjects'])." subject(s), "
				.$this->_results['loops']['base']." loops each, "
				.$this->_results['loops']['total']." in total.\n\n";
		}
	}

	/**
	 * Format value in bytes.
	 * 
	 * @param   mixed  $value
	 * @return  string
	 */
	protected function _formatMemory($value)
	{
		return \Text::bytes($value, 'MB', '%01.6f%s');
	}

	/**
	 * Format value in percents.
	 * 
	 * @param   mixed  $value
	 * @return  string
	 */
	protected function _formatRelative($value)
	{
		return ( (int) $value).'%';
	}

	/**
	 * Format value in seconds.
	 * 
	 * @param   mixed  $value
	 * @return  string
	 */
	protected function _formatTime($value)
	{
		return sprintf('%01.6f', $value).'s';
	}
}
