<?php

namespace CMS\EquipeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Equipes
 *
 * @ORM\Table(name="equipe_equipes")
 * @ORM\Entity(repositoryClass="CMS\EquipeBundle\Repository\EquipesRepository")
 */
class Equipes
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="poste", type="string", length=255 , nullable=true)
     */
    private $poste;

    /**
     * @var string
     *
     * @ORM\Column(name="apropos", type="string", length=1024 , nullable=true)
     */
    private $apropos;


    /**
     * @ORM\OneToOne(targetEntity="GalleryEquipes" ,cascade={"persist"})
     */
    private $galleries;

    /**
     * @ORM\OneToOne(targetEntity="Adresse" ,cascade={"persist"})
     */
    private $adresses;

    /**
     * @ORM\OneToMany(targetEntity="Social", mappedBy="equipes" ,cascade={"persist"})
     **/
    private $socials;

    /**
     * Equipes constructor.
     */
    public function __construct()
    {
        $this->socials = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Equipes
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Equipes
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set poste
     *
     * @param string $poste
     * @return Equipes
     */
    public function setPoste($poste)
    {
        $this->poste = $poste;

        return $this;
    }

    /**
     * Get poste
     *
     * @return string
     */
    public function getPoste()
    {
        return $this->poste;
    }

    /**
     * @return mixed
     */
    public function getGalleries()
    {
        return $this->galleries;
    }

    /**
     * @param mixed $galleries
     */
    public function setGalleries($galleries)
    {
        $this->galleries = $galleries;
    }

    /**
     * @return mixed
     */
    public function getAdresses()
    {
        return $this->adresses;
    }

    /**
     * @param mixed $adresses
     */
    public function setAdresses($adresses)
    {
        $this->adresses = $adresses;
    }


    /**
     * @return mixed
     */
    public function getApropos()
    {
        return $this->apropos;
    }

    /**
     * @param mixed $apropos
     */
    public function setApropos($apropos)
    {
        $this->apropos = $apropos;
    }

    /**
     * @return mixed
     */
    public function getSocials()
    {
        return $this->socials;
    }

    /**
     * @param mixed $socials
     */
    public function setSocials($socials)
    {
        $this->socials = $socials;
    }


    /**
     * @param Social $social
     * @return $this
     */
    public function addSocials(Social $social)
    {
        $this->socials[] = $social;
        return $this;
    }

    /**
     * @param Social $social
     */
    public function removeSocials(Social $social)
    {
        $this->socials->removeElement($social);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        $fullName = $this->getNom() . " " . $this->getPrenom();
        return $fullName;
    }
}
