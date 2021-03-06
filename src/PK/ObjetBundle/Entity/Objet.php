<?php

namespace PK\ObjetBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Objet
 *
 * @ORM\Table("pk_objet")
 * @ORM\Entity(repositoryClass="PK\ObjetBundle\Entity\ObjetRepository")
 */
class Objet
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
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\NotBlank(message = "Ce champ ne peut pas être nul")
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     *      minMessage = "Le titre de l'objet doit faire au moins {{ limit }} caractères",
     *      maxMessage = "Le titre de l'objet ne peut pas être plus long que {{ limit }} caractères"
     * )
     */
    private $titre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="actif", type="boolean")
     */
    private $actif = true;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Assert\Length(
     *      max = "2000",
     *      maxMessage = "La description de l'objet ne doit pas dépasser {{ limit }} caractères"
     * )
     */
    private $description;
    
    /**
    * @ORM\OneToMany(targetEntity="PK\PretBundle\Entity\Pret", mappedBy="objet", cascade={"remove", "persist"})
    */
    private $prets;
    
    /**
    * @ORM\ManyToOne(targetEntity="PK\UserBundle\Entity\User", inversedBy="objets")
    * @ORM\JoinColumn(nullable=false)
    */
    private $proprietaire;
    
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
     * Set titre
     *
     * @param string $titre
     * @return Objet
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return Objet
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set proprietaire
     *
     * @param \PK\UserBundle\Entity\User $proprietaire
     * @return Objet
     */
    public function setProprietaire(\PK\UserBundle\Entity\User $proprietaire)
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    /**
     * Get proprietaire
     *
     * @return \PK\UserBundle\Entity\User 
     */
    public function getProprietaire()
    {
        return $this->proprietaire;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->prets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add prets
     *
     * @param \PK\PretBundle\Entity\Pret $prets
     * @return Objet
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
    
    public function __toString() {
        return $this->titre."";
    }
    
    public function toArray() {
        $prets = array();
        foreach($this->prets as $pret) {
            $prets[] = $pret->toArray();
        }
        return array(
            'id' => $this->id,
            'titre' => $this->titre,
            'description' => $this->description,
            'proprietaire' => $this->proprietaire->getEmail(),
            'prets' => $prets
        );
    }
    public function toJson() {
        return json_encode($this->toArray());
    }

    /**
     * Set actif
     *
     * @param boolean $actif
     * @return Objet
     */
    public function setActif($actif)
    {
        $this->actif = $actif;

        return $this;
    }

    /**
     * Get actif
     *
     * @return boolean 
     */
    public function getActif()
    {
        return $this->actif;
    }
}
