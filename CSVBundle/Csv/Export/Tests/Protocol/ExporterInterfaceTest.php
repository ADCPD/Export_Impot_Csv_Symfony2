<?php

namespace Administration\CSVBundle\Csv\Export\Tests\Protocol;

use Administration\CSVBundle\Csv\Export\Protocol\Exception\IOException;

class ExporterInterfaceTest extends \PHPUnit_Framework_TestCase
{
    public function testExport()
    {
        $exporter = $this->getMock('Goodby\CSV\Export\Protocol\ExporterInterface');
        $exporter->expects($this->once())->method('export');

        $exporter->export('filename', array(
            array('ID', 'name',  'email'),
            array('1',  'alice', 'alice@example.com'),
            array('2',  'bob',   'bob@example.com'),
        ));
    }

    /**
     * @expectedException \Administration\CSVBundle\Csv\Export\Protocol\Exception\IOException
     */
    public function testExportsThrowsIOException()
    {
        $exporter = $this->getMock('Administration\CSVBundle\Csv\Export\Protocol\ExporterInterface');

        $exporter
            ->expects($this->once())
            ->method('export')
            ->will($this->throwException(new IOException('Unable to write')));

        $exporter->export('/path/to/file.csv', array(
            array('ID', 'name',  'email'),
            array('1',  'alice', 'alice@example.com'),
            array('2',  'bob',   'bob@example.com'),
        ));
    }
}
