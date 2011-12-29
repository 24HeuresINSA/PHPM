<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\Tache
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\TacheRepository")
 */
class Tache
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var text $description
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string $lieu
     *
     * @ORM\Column(name="lieu", type="string", length=255)
     */
    private $lieu;
    
    /**
    * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="taches")
    * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
    */
    protected $categorie;
    
    /**
    * @ORM\ManyToOne(targetEntity="Confiance", inversedBy="taches")
    * @ORM\JoinColumn(name="confiance_id", referencedColumnName="id")
    */
    protected $confiance;
    
    /**
    * @ORM\OneToMany(targetEntity="PlageHoraire", mappedBy="tache")
    */
    protected $plagesHoraire;


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
     * Set nom
     *
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set description
     *
     * @param text $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Get description
     *
     * @return text 
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     */
    public function setLieu($lieu)
    {
        $this->lieu = $lieu;
    }

    /**
     * Get lieu
     *
     * @return string 
     */
    public function getLieu()
    {
        return $this->lieu;
    }
    public function __construct()
    {
        $this->plagesHoraire = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set categorie
     *
     * @param PHPM\Bundle\Entity\Categorie $categorie
     */
    public function setCategorie(\PHPM\Bundle\Entity\Categorie $categorie)
    {
        $this->categorie = $categorie;
    }

    /**
     * Get categorie
     *
     * @return PHPM\Bundle\Entity\Categorie 
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * Set confiance
     *
     * @param PHPM\Bundle\Entity\Confiance $confiance
     */
    public function setConfiance(\PHPM\Bundle\Entity\Confiance $confiance)
    {
        $this->confiance = $confiance;
    }

    /**
     * Get confiance
     *
     * @return PHPM\Bundle\Entity\Confiance 
     */
    public function getConfiance()
    {
        return $this->confiance;
    }

    /**
     * Add plagesHoraire
     *
     * @param PHPM\Bundle\Entity\PlageHoraire $plagesHoraire
     */
    public function addPlageHoraire(\PHPM\Bundle\Entity\PlageHoraire $plagesHoraire)
    {
        $this->plagesHoraire[] = $plagesHoraire;
    }

    /**
     * Get plagesHoraire
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlagesHoraire()
    {
        return $this->plagesHoraire;
    }
    
    public function __toString()
    {
    	return $this->getNom();
    }
    
    public function toArray()
    {
    	foreach ($this->getPlagesHoraire() as $entity){
    		$a[$entity->getId()] = $entity->toArray();
    	
    	}
    	
    	return array(
    	"nom" => $this->getDebut(),
    	"lieu" => $this->getLieu(),
    	"confiance" => $this->getConfiance()->toArray(),
    	"categorie" => $this->getCategorie()->toArray(),
    	"palgesHoraire" => $a);
    	
    }
    
}