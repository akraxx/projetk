<?php

namespace PK\PretBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Pret
 *
 * @ORM\Table("pk_pret")
 * @ORM\Entity(repositoryClass="PK\PretBundle\Entity\PretRepository")
 */
class Pret
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
    * @ORM\ManyToOne(targetEntity="PK\ObjetBundle\Entity\Objet", inversedBy="prets")
    * @ORM\JoinColumn(nullable=false)
    */
    private $objet;
    
    /**
     * @var integer
     *
     * @ORM\Column(name="typeEmprunteur", type="integer")
     */
    private $typeEmprunteur;
    
    /**
    * @ORM\ManyToOne(targetEntity="PK\UserBundle\Entity\User", inversedBy="emprunteurs")
    * @ORM\JoinColumn(nullable=true)
    */
    private $userEmprunteur;
    
    /**
     * @var string
     *
     * @ORM\Column(name="emailEmprunteur", type="string", length=255, nullable=true)
     * @Assert\Email(
     *     message = "'{{ value }}' n'est pas un email valide."
     * )
     */
    private $emailEmprunteur;
    
    /**
     * @var string
     *
     * @ORM\Column(name="emprunteur", type="string", length=255, nullable=true)
     * @Assert\Length(
     *      max = "50",
     *      maxMessage = "Le nom de l'emprunteur ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $emprunteur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rendu", type="boolean")
     */
    private $rendu = false;
    
    /**
     * @var boolean
     *
     * @ORM\Column(name="confirme", type="boolean")
     */
    private $confirme = false;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_rendu", type="date", nullable=true)
     */
    private $dateRendu;
    
    /**
    * @ORM\ManyToOne(targetEntity="PK\UserBundle\Entity\User", inversedBy="prets")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;
    
    /**
    * @ORM\OneToOne(targetEntity="PK\PretBundle\Entity\Pret", inversedBy="pretEnfant")
    * @ORM\JoinColumn(nullable=true)
    */
    private $pretParent;
    
    /**
    * @ORM\OneToOne(targetEntity="PK\PretBundle\Entity\Pret", mappedBy="pretParent")
    */
    private $pretEnfant;
    
    public function __construct() {
        $this->date = new \DateTime();
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
     * Set objet
     *
     * @param string $objet
     * @return Pret
     */
    public function setObjet($objet)
    {
        $this->objet = $objet;

        return $this;
    }

    /**
     * Get objet
     *
     * @return string 
     */
    public function getObjet()
    {
        return $this->objet;
    }

    /**
     * Set emprunteur
     *
     * @param string $emprunteur
     * @return Pret
     */
    public function setEmprunteur($emprunteur)
    {
        $this->emprunteur = $emprunteur;

        return $this;
    }

    /**
     * Get emprunteur
     *
     * @return string 
     */
    public function getEmprunteur()
    {
        return $this->emprunteur;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     * @return Pret
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
     * Set rendu
     *
     * @param boolean $rendu
     * @return Pret
     */
    public function setRendu($rendu)
    {
        $this->rendu = $rendu;

        return $this;
    }

    /**
     * Get rendu
     *
     * @return boolean 
     */
    public function getRendu()
    {
        return $this->rendu;
    }

    /**
     * Set dateRendu
     *
     * @param \DateTime $dateRendu
     * @return Pret
     */
    public function setDateRendu($dateRendu)
    {
        $this->dateRendu = $dateRendu;

        return $this;
    }

    /**
     * Get dateRendu
     *
     * @return \DateTime 
     */
    public function getDateRendu()
    {
        return $this->dateRendu;
    }

    /**
     * Set user
     *
     * @param \PK\UserBundle\Entity\User $user
     * @return Pret
     */
    public function setUser(\PK\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \PK\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
    
    public function __toString() {
        return $this->objet->getTitre()."";
    }
    
    public function toArray() {
        return array(
            'id' => $this->id,
            'date' => $this->date,
            'dateRendu' => $this->dateRendu,
            'rendu' => $this->rendu,
            'emprunteur' => $this->emprunteur
        );
    }
    public function toJson() {
        return json_encode($this->toArray());
    }

    /**
     * Set typeEmprunteur
     *
     * @param integer $typeEmprunteur
     * @return Pret
     */
    public function setTypeEmprunteur($typeEmprunteur)
    {
        $this->typeEmprunteur = $typeEmprunteur;

        return $this;
    }

    /**
     * Get typeEmprunteur
     *
     * @return integer 
     */
    public function getTypeEmprunteur()
    {
        return $this->typeEmprunteur;
    }

    /**
     * Set emailEmprunteur
     *
     * @param string $emailEmprunteur
     * @return Pret
     */
    public function setEmailEmprunteur($emailEmprunteur)
    {
        $this->emailEmprunteur = $emailEmprunteur;

        return $this;
    }

    /**
     * Get emailEmprunteur
     *
     * @return string 
     */
    public function getEmailEmprunteur()
    {
        return $this->emailEmprunteur;
    }

    /**
     * Set userEmprunteur
     *
     * @param \PK\UserBundle\Entity\User $userEmprunteur
     * @return Pret
     */
    public function setUserEmprunteur(\PK\UserBundle\Entity\User $userEmprunteur = null)
    {
        $this->userEmprunteur = $userEmprunteur;

        return $this;
    }

    /**
     * Get userEmprunteur
     *
     * @return \PK\UserBundle\Entity\User 
     */
    public function getUserEmprunteur()
    {
        return $this->userEmprunteur;
    }

    /**
     * Set confirme
     *
     * @param boolean $confirme
     * @return Pret
     */
    public function setConfirme($confirme)
    {
        $this->confirme = $confirme;

        return $this;
    }

    /**
     * Get confirme
     *
     * @return boolean 
     */
    public function getConfirme()
    {
        return $this->confirme;
    }

    /**
     * Set pretEnfant
     *
     * @param \PK\PretBundle\Entity\Pret $pretEnfant
     * @return Pret
     */
    public function setPretEnfant(\PK\PretBundle\Entity\Pret $pretEnfant = null)
    {
        $this->pretEnfant = $pretEnfant;

        return $this;
    }

    /**
     * Get pretEnfant
     *
     * @return \PK\PretBundle\Entity\Pret 
     */
    public function getPretEnfant()
    {
        return $this->pretEnfant;
    }

    /**
     * Set pretParent
     *
     * @param \PK\PretBundle\Entity\Pret $pretParent
     * @return Pret
     */
    public function setPretParent(\PK\PretBundle\Entity\Pret $pretParent = null)
    {
        $this->pretParent = $pretParent;

        return $this;
    }

    /**
     * Get pretParent
     *
     * @return \PK\PretBundle\Entity\Pret 
     */
    public function getPretParent()
    {
        return $this->pretParent;
    }
}
