<?php
namespace Cz\Codebench\Printers;

/**
 * Benchmark results printer.
 * 
 * @uses  ArrayToTextTable
 * 
 * @package    Framework
 * @category   Codebench
 * @author     Korney Czukowski
 * @copyright  (c) 2015 Korney Czukowski
 * @license    MIT License
 */
class ByMethod extends Printer
{
	/**
	 * Prints Codebench results.
	 */
	public function render()
	{
		$this->printHeader();
		foreach ($this->_results['benchmarks'] as $method => $benchmark)
		{
			$this->_printBenchmark($method, $benchmark);
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
}
