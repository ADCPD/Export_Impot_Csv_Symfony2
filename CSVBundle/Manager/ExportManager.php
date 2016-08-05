<?php

namespace Administration\CSVBundle\Manager;

use Administration\UserBundle\Manager\BaseManager;
use Doctrine\ORM\EntityManager;

/**
 * Class ExportManager
 * @package Administration\CSVBundle\Manager
 */
class ExportManager extends BaseManager
{
    private $filename;

    /**
     * ExportManager constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @return mixed
     */
    public function getRepository()
    {
        // TODO: Implement getRepository() method.
    }

    /**
     * @param $filename
     * @return string
     */
    public function getNameCsvFile($filename)
    {
        return $this->filename = 'export_' . $filename . '_' . date("Y_m_d_His") . ".csv";
    }


}