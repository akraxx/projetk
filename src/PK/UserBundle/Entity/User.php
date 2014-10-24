<?php

namespace PK\UserBundle\Entity;

use Sonata\UserBundle\Entity\BaseUser as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ce_user")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /** @ORM\Column(name="facebook_id", type="integer", nullable=true) */
    protected $facebook_id;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
    protected $facebook_access_token;
    
    /**
    * @ORM\OneToMany(targetEntity="PK\PretBundle\Entity\Pret", mappedBy="user")
    */
    private $prets;
    
    /**
    * @ORM\OneToMany(targetEntity="PK\PretBundle\Entity\Pret", mappedBy="userEmprunteur")
    */
    private $emprunteurs;
    
    /**
    * @ORM\OneToMany(targetEntity="PK\ObjetBundle\Entity\Objet", mappedBy="proprietaire")
    */
    private $objets;
    
    /**
    * @ORM\OneToMany(targetEntity="PK\UserBundle\Entity\Notification", mappedBy="user")
    */
    private $notifications;
    
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
     * Set facebook_id
     *
     * @param string $facebookId
     * @return User
     */
    public function setFacebookId($facebookId)
    {
        $this->facebook_id = $facebookId;

        return $this;
    }

    /**
     * Get facebook_id
     *
     * @return string 
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Set facebook_access_token
     *
     * @param string $facebookAccessToken
     * @return User
     */
    public function setFacebookAccessToken($facebookAccessToken)
    {
        $this->facebook_access_token = $facebookAccessToken;

        return $this;
    }

    /**
     * Get facebook_access_token
     *
     * @return string 
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->prets = new \Doctrine\Common\Collections\ArrayCollection();
        $this->objets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add prets
     *
     * @param \PK\PretBundle\Entity\Pret $prets
     * @return User
     */
    public function addPret(\PK\PretBundle\Entity\Pret $prets)
    {
        $this->prets[] = $prets;

        return $this;
    }

    /**
     * Remove prets
     *
     * @param \PK\PretBundle\Entity\Pret $prets
     */
    public function removePret(\PK\PretBundle\Entity\Pret $prets)
    {
        $this->prets->removeElement($prets);
    }

    /**
     * Get prets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPrets()
    {
        return $this->prets;
    }


    /**
     * Add objets
     *
     * @param \PK\PretBundle\Entity\Pret $objets
     * @return User
     */
    public function addObjet(\PK\PretBundle\Entity\Pret $objets)
    {
        $this->objets[] = $objets;

        return $this;
    }

    /**
     * Remove objets
     *
     * @param \PK\PretBundle\Entity\Pret $objets
     */
    public function removeObjet(\PK\PretBundle\Entity\Pret $objets)
    {
        $this->objets->removeElement($objets);
    }

    /**
     * Get objets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getObjets()
    {
        return $this->objets;
    }

    
    /**
     * Add emprunteurs
     *
     * @param \PK\PretBundle\Entity\Pret $emprunteurs
     * @return User
     */
    public function addEmprunteur(\PK\PretBundle\Entity\Pret $emprunteurs)
    {
        $this->emprunteurs[] = $emprunteurs;

        return $this;
    }

    /**
     * Remove emprunteurs
     *
     * @param \PK\PretBundle\Entity\Pret $emprunteurs
     */
    public function removeEmprunteur(\PK\PretBundle\Entity\Pret $emprunteurs)
    {
        $this->emprunteurs->removeElement($emprunteurs);
    }

    /**
     * Get emprunteurs
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmprunteurs()
    {
        return $this->emprunteurs;
    }

    /**
     * Add notifications
     *
     * @param \PK\UserBundle\Entity\Notification $notifications
     * @return User
     */
    public function addNotification(\PK\UserBundle\Entity\Notification $notifications)
    {
        $this->notifications[] = $notifications;

        return $this;
    }

    /**
     * Remove notifications
     *
     * @param \PK\UserBundle\Entity\Notification $notifications
     */
    public function removeNotification(\PK\UserBundle\Entity\Notification $notifications)
    {
        $this->notifications->removeElement($notifications);
    }

    /**
     * Get notifications
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNotifications()
    {
        return $this->notifications;
    }
}
