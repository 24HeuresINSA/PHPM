<?php

namespace PHPM\Bundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
    * @var integer $importId
    *
    * @ORM\Column(name="importid", type="integer", nullable=true)
    */
    private $importId;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $nom;

    /**
     * @var text $consignes
     *
     * @ORM\Column(name="consignes", type="text")
     * @Assert\NotBlank()
     */
    private $consignes;
    
    /**
    * @var text $materielNecessaire
    *
    * @ORM\Column(name="materielNecessaire", type="text", nullable=true)
    */
    private $materielNecessaire;
    
    
    /**
    * @var smallint $permisNecessaire
    *
    * @ORM\Column(name="permisNecessaire", type="smallint")
    * @Assert\Min(limit = "0")
    */
    private $permisNecessaire;

    /**
    * @var smallint $ageNecessaire
    *
    * @ORM\Column(name="ageNecessaire", type="smallint")
    * @Assert\Min(limit = "0")
    */
    private $ageNecessaire;
    
    
    /**
     * @var string $lieu
     *
     * @ORM\Column(name="lieu", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $lieu;
    
    
    /**
    * @ORM\ManyToOne(targetEntity="Orga", inversedBy="taches")
    * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id")
    * @Assert\Valid
    */
    private $responsable;
    
    /**
    * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="taches")
    * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id")
    * @Assert\Valid
    */
    protected $categorie;
    
    /**
    * @ORM\ManyToOne(targetEntity="Confiance", inversedBy="taches")
    * @ORM\JoinColumn(name="confiance_id", referencedColumnName="id")
    * @Assert\Valid
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
    
    public function toArray($developCreneaux = NULL)
    {
    	$a = array();
    	if(isset($developCreneaux))
    	foreach ($this->getPlagesHoraire() as $entity){
    		$a[$entity->getId()] = $entity->toArray(TRUE);
    	}
    	
    	
    	return array(
    	"id" => $this->getId(),
    	"importId" => $this->getImportId(),
    	"nom" => $this->getNom(),
    	"lieu" => $this->getLieu(),
    	"materielNecessaire" => $this->getMaterielNecessaire(),
    	"consignes" => $this->getConsignes(),
    	"confiance" => $this->getConfiance()->toArray(),
    	"categorie" => $this->getCategorie()->toArray(),
    	"permisNecessaire" => $this->getPermisNecessaire(),
    	"ageNecessaire" => $this->getAgeNecessaire(),
    	"plagesHoraire" => $a);
    	
    }
    



    /**
     * Set permisNecessaire
     *
     * @param smallint $permisNecessaire
     */
    public function setPermisNecessaire($permisNecessaire)
    {
        $this->permisNecessaire = $permisNecessaire;
    }

    /**
     * Get permisNecessaire
     *
     * @return smallint 
     */
    public function getPermisNecessaire()
    {
        return $this->permisNecessaire;
    }

    /**
     * Set consignes
     *
     * @param text $consignes
     */
    public function setConsignes($consignes)
    {
        $this->consignes = $consignes;
    }

    /**
     * Get consignes
     *
     * @return text 
     */
    public function getConsignes()
    {
        return $this->consignes;
    }

    /**
     * Set materielNecessaire
     *
     * @param text $materielNecessaire
     */
    public function setMaterielNecessaire($materielNecessaire)
    {
        $this->materielNecessaire = $materielNecessaire;
    }

    /**
     * Get materielNecessaire
     *
     * @return text 
     */
    public function getMaterielNecessaire()
    {
        return $this->materielNecessaire;
    }

    /**
     * Set importId
     *
     * @param integer $importId
     */
    public function setImportId($importId)
    {
        $this->importId = $importId;
    }

    /**
     * Get importId
     *
     * @return integer 
     */
    public function getImportId()
    {
        return $this->importId;
    }

    /**
     * Set ageNecessaire
     *
     * @param smallint $ageNecessaire
     */
    public function setAgeNecessaire($ageNecessaire)
    {
        $this->ageNecessaire = $ageNecessaire;
    }

    /**
     * Get ageNecessaire
     *
     * @return smallint 
     */
    public function getAgeNecessaire()
    {
        return $this->ageNecessaire;
    }

    /**
     * Set responsable
     *
     * @param PHPM\Bundle\Entity\Orga $responsable
     */
    public function setResponsable(\PHPM\Bundle\Entity\Orga $responsable)
    {
        $this->responsable = $responsable;
    }

    /**
     * Get responsable
     *
     * @return PHPM\Bundle\Entity\Orga 
     */
    public function getResponsable()
    {
        return $this->responsable;
    }
}