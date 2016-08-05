<?php

namespace CMS\EquipeBundle\Manager;

use Administration\UserBundle\Manager\BaseManager;
use Doctrine\ORM\EntityManager;

/**
 * Class EquipeManager
 * @package CMS\EquipeBundle\Manager
 */
class EquipeManager extends BaseManager
{

    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * @return mixed
     */
    function getRepository()
    {
        return $this->em->getRepository("EquipeBundle:Equipes");
    }

    /**
     * Afficher les informations des collaborateurs
     * @return mixed
     */
    function getFullEquipes()
    {
        $qb = $this->getRepository()
            ->getEquipeInformations()
            ->getQuery()
            ->getResult();
        return $qb;
    }


    function getSqlEquipes()
    {
        $requette = $this->getRepository()
            ->getEquipeInformations()
            ->getQuery()
            ->getSql();
        return $requette;
    }
}