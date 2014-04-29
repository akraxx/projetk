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
     * @var string
     *
     * @ORM\Column(name="objet", type="string", length=255)
     * @Assert\NotBlank(message = "Ce champ ne peut pas être nul")
     * @Assert\Length(
     *      min = "2",
     *      max = "255",
     *      minMessage = "Votre objet doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Votre objet ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $objet;

    /**
     * @var string
     *
     * @ORM\Column(name="emprunteur", type="string", length=255)
     * @Assert\NotBlank(message = "Ce champ ne peut pas être nul")
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     *      minMessage = "Le nom de l'emprunteur doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Le nom de l'emprunteur ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $emprunteur;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var boolean
     *
     * @ORM\Column(name="rendu", type="boolean")
     */
    private $rendu = false;

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
}
