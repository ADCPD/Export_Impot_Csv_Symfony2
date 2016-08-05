<?php

namespace CMS\EquipeBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Social
 *
 * @ORM\Table(name="equipe_social_equipes")
 * @ORM\Entity(repositoryClass="CMS\EquipeBundle\Repository\SocialRepository")
 */
class Social
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
     * @ORM\Column(name="type", type="string", length=255, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=500, nullable=false)
     */
    private $url;

    /**
     * @ORM\ManyToOne(targetEntity="Equipes", inversedBy="socials")
     * @ORM\JoinColumn(name="equipe_id", nullable=true)
     */
    private $equipes;


    /**
     *
     */
    public function __contruct()
    {
        $this->equipes = new ArrayCollection();
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
     * Set type
     *
     * @param string $type
     * @return Social
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Social
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return mixed
     */
    public function getEquipes()
    {
        return $this->equipes;
    }

    /**
     * @param mixed $equipes
     */
    public function setEquipes($equipes)
    {
        $this->equipes = $equipes;
    }
}
