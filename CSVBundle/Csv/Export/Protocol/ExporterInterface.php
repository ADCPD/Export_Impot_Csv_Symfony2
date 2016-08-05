<?php

namespace Administration\CSVBundle\Csv\Export\Protocol;

use Administration\CSVBundle\Csv\Export\Protocol\Exception\IOException;
use Traversable;

/**
 * Interface of the Exporter
 */
interface ExporterInterface
{
    /**
     * Export data as CSV file
     *
     * @param string $filename
     * @param array|Traversable $rows
     * @throws IOException
     * @return void
     */
    public function export($filename, $rows);
}
