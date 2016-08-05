<?php
/**
 * Created by PhpStorm.
 * User: dhaouadi_a
 * Date: 28/07/2016
 * Time: 11:28
 */

namespace CMS\EquipeBundle\Entity;

use Administration\UploadBundle\Consts\PathToUploadConst;
use Administration\UploadBundle\Traits\CycleCallBackTrait;
use Administration\UploadBundle\Traits\FileUploader;
use Doctrine\ORM\Mapping as ORM;


/**
 * Gallery
 *
 * @ORM\Table(name="equipe_gallery_equipes")
 * @ORM\Entity(repositoryClass="CMS\ProjetCollectionBundle\Repository\GalleryRepository")
 */
class GalleryEquipes
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    private $direcion_file_path = PathToUploadConst::section_equipe;

    use FileUploader;

    use CycleCallBackTrait;

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
}
