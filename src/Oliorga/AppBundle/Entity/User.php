<?php

namespace Oliorga\AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="oliorga__app__user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * Nuri Person linked to this User
     * @var Nutri\RecipeBundle\Entity\Person
     * 
     * @ORM\OneToOne(targetEntity="Nutri\RecipeBundle\Entity\Person", mappedBy="user", cascade={"persist"}))
     */
    private $person;
    
    /**************************************************************************/
    ////////////////////////////////////////////////////////////////////////////
    //                  Attributes' setters and getters
    ////////////////////////////////////////////////////////////////////////////

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
     * Set person
     *
     * @param \Nutri\RecipeBundle\Entity\Person $person
     * @return User
     */
    public function setPerson(\Nutri\RecipeBundle\Entity\Person $person = null)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return \Nutri\RecipeBundle\Entity\Person 
     */
    public function getPerson()
    {
        return $this->person;
    }
}
