<?php
namespace Cz\Codebench;

/**
 * Benchmark results printer.
 * 
 * @uses  ArrayToTextTable
 * 
 * @package    Framework
 * @category   Callbacks
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
class ResultPrinter
{
	/**
	 * @var  array
	 */
	private $_results;

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
	public function printResults()
	{
		$this->_printHeader();
		foreach ($this->_results['benchmarks'] as $method => $benchmark)
		{
			$this->_printBenchmark($method, $benchmark);
		}
	}

	/**
	 * Print results header.
	 */
	private function _printHeader()
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
			echo count($this->_results['subjects'])." subject(s), "
				.$this->_results['loops']['base']." loops each, "
				.$this->_results['loops']['total']." total.\n\n";
		}
	}

	/**
	 * Print benchmark details.
	 * 
	 * @param  string  $method     Method name
	 * @param  array   $benchmark  Method benchmark results
	 */
	private function _printBenchmark($method, $benchmark)
	{
		echo "Benchmarks per subject for `$method` method:\n";
		echo "Total method memory: ".$this->_formatMemory($benchmark['memory'])
			." (".$this->_formatRelative($benchmark['percent']['fastest']['memory']).")\n";
		echo "Total method time: ".$this->_formatTime($benchmark['time'])
			." (".$this->_formatRelative($benchmark['percent']['fastest']['time']).")\n";

		$subjects = $this->_mapToArrayToTextTable($benchmark['subjects']);

		$renderer = new \ArrayToTextTable($subjects);
		$renderer->showHeaders(TRUE);
		$renderer->render();

		echo "\n\n";
	}

	/**
	 * Convert benchmark subject to flat array for tabular output.
	 * 
	 * @param   array  $subjects
	 * @return  array
	 */
	private function _mapToArrayToTextTable(array $subjects)
	{
		$output = array();
		foreach ($subjects as $key => $subject) {
			$output[] = array(
				'subject' => $key,
				'return type' => gettype($subject['return']),
				'time' => $this->_formatTime($subject['time']),
				'time rel' => $this->_formatRelative($subject['percent']['fastest']['time']),
				'time grade' => $subject['grade']['time'],
				'memory' => $this->_formatMemory($subject['memory']),
				'memory rel' => $this->_formatRelative($subject['percent']['fastest']['memory']),
				'memory grade' => $subject['grade']['memory'],
			);
		}
		return $output;
	}

	/**
	 * Format value in bytes.
	 * 
	 * @param   mixed  $value
	 * @return  string
	 */
	private function _formatMemory($value)
	{
		return \Text::bytes($value, 'MB', '%01.6f%s');
	}

	/**
	 * Format value in percents.
	 * 
	 * @param   mixed  $value
	 * @return  string
	 */
	private function _formatRelative($value)
	{
		return ( (int) $value).'%';
	}

	/**
	 * Format value in seconds.
	 * 
	 * @param   mixed  $value
	 * @return  string
	 */
	private function _formatTime($value)
	{
		return sprintf('%01.6f', $value).'s';
	}
}
