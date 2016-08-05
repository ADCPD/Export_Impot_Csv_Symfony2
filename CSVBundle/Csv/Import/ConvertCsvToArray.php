<?php
/**
 * Created by PhpStorm.
 * User: dhaouadi_a
 * Date: 04/08/2016
 * Time: 18:44
 */

namespace Administration\CSVBundle\Csv\Import;

/**
 * Class ConvertCsvToArray
 * @package Administration\CSVBundle\Csv\Import
 */
class ConvertCsvToArray
{
    /**
     * ConvertCsvToArray constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $filename
     * @param string $delimiter
     * @return array|bool
     */
    public function convert($filename, $delimiter = ',')
    {
        if (!file_exists($filename) || !is_readable($filename)) {
            return FALSE;
        }

        $header = NULL;
        $data = array();

        if (($handle = fopen($filename, 'r')) !== FALSE) {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== FALSE) {
                if (!$header) {
                    $header = $row;
                } else {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }
        return $data;
    }

}