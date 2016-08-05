<?php

namespace Administration\CSVBundle\Csv\Export\Tests\Standard\Unit\Collection;
use \ArrayIterator;

/**
 * Class SampleAggIterator
 * @package Administration\CSVBundle\Csv\Export\Tests\Standard\Unit\Collection
 */
class SampleAggIterator implements \IteratorAggregate
{
	protected $data;
	
	public function __construct($data)
	{
		$this->data = $data;
	}
	
	public function getIterator()
	{
		return new ArrayIterator($this->data);
	}
}
