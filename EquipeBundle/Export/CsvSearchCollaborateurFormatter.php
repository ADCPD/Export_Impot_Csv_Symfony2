<?php

/**
 * Created by PhpStorm.
 * User: dhaouadi_a
 * Date: 03/08/2016
 * Time: 11:06
 */

namespace CMS\EquipeBundle\Export;

use Administration\ExportCSVBundle\Model\CsvFormatterAbstract;
use CMS\EquipeBundle\Entity\Equipes;
use CMS\EquipeBundle\Manager\EquipeManager;

/**
 * Class CsvSearchCollaborateurFormatter
 * @package CMS\EquipeBundle\Export
 */
class CsvSearchCollaborateurFormatter extends CsvFormatterAbstract
{
    /** @var  EquipeManager $EquipeManager */
    protected $EquipeManager;
    protected $_cacheEquipe = array();

    /**
     * CsvSearchCollaborateurFormatter constructor.
     * @param EquipeManager $EquipeManager
     */
    public function __construct(EquipeManager $EquipeManager)
    {
        $this->EquipeManager = $EquipeManager;
    }

    /**
     * @param Equipes $collab
     * @return mixed
     */
    private function getCollaborateur(Equipes $collab)
    {
        if (!array_key_exists($collab->getNom(), $this->_cacheEquipe)) {
            $this->_cacheEquipe[$collab->getNom()] = $this->EquipeManager->getEquipes();
        }
        return $this->_cacheEquipe[$collab->getNom()];
    }

    /**
     * @param $obj
     * @param array $context
     * @return array
     */
    public function toArray($obj, array $context = array())
    {

        if ($obj instanceof Equipes) {
            $collab = $this->EquipeManager->getEquipes();

            return array(
                "nom" => $collab->getNom(),
                "prenom" => $collab->getPrenom(),
                "poste" => $collab->getPoste(),
                "apropos" => $collab->getApropos(),
                "adresses_postale" => $collab->getAdresses()->getAdresse(),
                "E-mail" => $collab->getAdresses()->getMail(),
                "N°Portable" => $collab->getAdresses()->getTel(),
                "N°Fix" => $collab->getAdresses()->getFix(),
            );

        } else {
            throw new \InvalidArgumentException("L'objet n'est pas de la classe attendue {" . get_class($obj) . "] au lieu de {Equipe}");
        }
    }

    /**
     * @param array $context
     * @return array
     */
    public function getHeader(array $context = array())
    {
        return array(
            "nom",
            "prenom",
            "poste",
            "apropos",
            "adresses_postale",
            "E-mail",
            "N°Portable",
            "N°Fix"
        );
    }


}