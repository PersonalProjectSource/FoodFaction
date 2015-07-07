<?php

namespace IntentRestAPI\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use IntentRestAPI\UserBundle\Entity\MainUser;
use IntentRestAPI\UserBundle\Entity\Message;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IntentRestAPI\MainBundle\Entity\Repository\UserRepository")
 */
class User extends MainUser
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="Commande", mappedBy="user",cascade={"persist"})
     */
    protected $commandes;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}
