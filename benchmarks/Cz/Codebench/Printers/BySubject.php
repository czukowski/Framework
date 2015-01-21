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
class BySubject extends Printer
{
	/**
	 * Prints Codebench results.
	 */
	public function printResults()
	{
		$this->printHeader();
		foreach (array_keys($this->_results['subjects']) as $key)
		{
			$this->_printSubject($key);
		}
	}

	private function _printSubject($subjectKey)
	{
		echo "Benchmarks per method for `$subjectKey` subject:\n";

		$subjects = $this->_mapToArrayToTextTable($this->_results['benchmarks'], $subjectKey);

		$renderer = new \ArrayToTextTable($subjects);
		$renderer->showHeaders(TRUE);
		$renderer->render();

		echo "\n\n";
	}

	private function _mapToArrayToTextTable($benchmarks, $subjectKey)
	{
		$output = array();
		foreach ($benchmarks as $method => $benchmark)
		{
			$subject = $benchmark['subjects'][$subjectKey];
			$output[] = array(
				'method' => $method,
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
