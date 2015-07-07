<?php

namespace IntentRestAPI\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="IntentRestAPI\UserBundle\Entity\Repositories\NotificationRepository")
 */
class Notification implements EngineObject
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
     * @ORM\ManyToOne(targetEntity="IntentRestAPI\MainBundle\Entity\User", cascade={"persist"})
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=255)
     */
    private $picture;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=255)
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="string")
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="typeNotif", type="string", length=255)
     */
    private $typeNotif;

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
     * Set picture
     *
     * @param string $picture
     * @return Notification
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return string 
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Notification
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Notification
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set typeNotif
     *
     * @param string $typeNotif
     * @return Notification
     */
    public function setTypeNotif($typeNotif)
    {
        $this->typeNotif = $typeNotif;

        return $this;
    }

    /**
     * Get typeNotif
     *
     * @return string 
     */
    public function getTypeNotif()
    {
        return $this->typeNotif;
    }

    /**
     * Transforme un objet en array
     * Methode devant obligatoirement etre surchargée par les classes filles.
     */
    public function spawnInArray() {
        foreach ($this as $sAttribName => $mAttribValue) {
            if (!is_object($mAttribValue)) {
                $aProduit[$sAttribName] = $mAttribValue;
            }
        }
        return $aProduit;
    }

    /**
     * Set user
     *
     * @param \IntentRestAPI\MainBundle\Entity\User $user
     * @return Notification
     */
    public function setUser(\IntentRestAPI\MainBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \IntentRestAPI\MainBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}
