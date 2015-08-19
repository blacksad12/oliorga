<?php

namespace Nutri\IngredientBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Aliment
 *
 * @ORM\Table(name="nutri__ingredient__aliment")
 * @ORM\Entity(repositoryClass="Nutri\IngredientBundle\Entity\AlimentRepository")
 */
class Aliment
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
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="This value is mandatory: name")
     * @Assert\Length(max=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="barcode", type="integer", nullable=true)
     */
    private $barcode;


    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                             To String
    ////////////////////////////////////////////////////////////////////////////
    public function __toString() {
        return strval($this->getId());
    }
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                  Custom setters and getters
    ////////////////////////////////////////////////////////////////////////////
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                  Attributes' setters and getters
    ////////////////////////////////////////////////////////////////////////////

    /**
     * Get id
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set name
     * @param string $name
     * @return Aliment
     */
    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     * @return string 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * Set barcode
     * @param integer $barcode
     * @return Aliment
     */
    public function setBarcode($barcode) {
        $this->barcode = $barcode;
        return $this;
    }

    /**
     * Get barcode
     * @return integer 
     */
    public function getBarcode() {
        return $this->barcode;
    }
}
