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
    protected $id;
    
    /**
    * @var integer $importId
    *
    * @ORM\Column(name="importid", type="integer", nullable=true)
    */
    protected $importId;

    /**
     * @var string $nom
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $nom;

    /**
     * @var text $consignes
     *
     * @ORM\Column(name="consignes", type="text")
     * @Assert\NotBlank()
     */
    protected $consignes;
    
    /**
     * @var text $materielSupplementaire
     *
     * @ORM\Column(name="materielSupplementaire", type="text")
     * 
     */
    protected $materielSupplementaire;
    
    
    /**
    * @var smallint $permisNecessaire
    *
    * @ORM\Column(name="permisNecessaire", type="smallint")
    * @Assert\Min(limit = "0")
    */
    protected $permisNecessaire;

    /**
    * @var smallint $ageNecessaire
    *
    * @ORM\Column(name="ageNecessaire", type="smallint")
    * @Assert\Min(limit = "0")
    */
    protected $ageNecessaire;
    
    

    /**
     * @ORM\ManyToOne(targetEntity="Lieu", inversedBy="taches")
     * @ORM\JoinColumn(name="lieu_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
     * @Assert\Valid
     */
    protected $lieu;
    
    
    /**
    * @ORM\ManyToOne(targetEntity="Orga", inversedBy="tachesResponsable")
    * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
    * @Assert\Valid
    */
    protected $responsable;
    
    /**
    * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="taches")
    * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
    * @Assert\Valid
    */
    protected $categorie;
    
    /**
    * @ORM\ManyToOne(targetEntity="Confiance", inversedBy="taches")
    * @ORM\JoinColumn(name="confiance_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
    * @Assert\Valid
    */
    protected $confiance;
    
    /**
    * @ORM\OneToMany(targetEntity="PlageHoraire", mappedBy="tache", indexBy="id",orphanRemoval=true, cascade={"persist", "remove"})
    */
    protected $plagesHoraire;
    
    
    /**
     * @ORM\ManyToOne(targetEntity="GroupeTache", inversedBy="taches")
     * @ORM\JoinColumn(name="groupetache_id", referencedColumnName="id",onDelete="CASCADE", onUpdate="CASCADE")
     * @Assert\Valid
     */
    protected $groupeTache;
    
    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"0", "1", "2"})
     * @ORM\Column(name="statut", type="smallint")
     */
    protected $statut;
    
    /**
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="tache", indexBy="id")
     */
    protected $commentaires;
    
    /**
    * @ORM\OneToMany(targetEntity="BesoinMateriel", mappedBy="tache", indexBy="id")
    */
    protected $besoinsMateriel;

  
    
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
    public function addPlageHoraire(\PHPM\Bundle\Entity\PlageHoraire $plageHoraire)
    {
        var_dump("ap");
        $plageHoraire->setTache($this);
        $this->plagesHoraire[] = $plageHoraire;
    }
    
    /**
     * Set plagesHoraire
     *
     * @param PHPM\Bundle\Entity\PlageHoraire $plagesHoraire
     */
    public function setPlagesHoraire($plagesHoraire)
    {
        foreach ($plagesHoraire as $pl)
            $pl->setTache($this);
        $this->plagesHoraire = $plagesHoraire;
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
        return $this->getId()."- ".$this->getNom();
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
    	"consignes" => $this->getConsignes(),
    	"confiance" => $this->getConfiance()->toArray(),
    	"categorie" => $this->getCategorie()->toArray(),
    	"permisNecessaire" => $this->getPermisNecessaire(),
    	"ageNecessaire" => $this->getAgeNecessaire(),
    	"plagesHoraire" => $a);
    	
    }
    
    public function toArrayOrgaWebService($developCreneaux = NULL)
    {
 
    	return array(
        	"id" => $this->getId(),
        	"nom" => $this->getNom(),
        	"lieu" => $this->getLieu(),
        	);
    	 
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

    /**
     * Set statut
     *
     * @param smallint $statut
     */
    public function setStatut($statut)
    {
        $this->statut = $statut;
    }

    /**
     * Get statut
     *
     * @return smallint 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set groupeTache
     *
     * @param PHPM\Bundle\Entity\GroupeTache $groupeTache
     */
    public function setGroupeTache(\PHPM\Bundle\Entity\GroupeTache $groupeTache)
    {
        $this->groupeTache = $groupeTache;
    }

    /**
     * Get groupeTache
     *
     * @return PHPM\Bundle\Entity\GroupeTache 
     */
    public function getGroupeTache()
    {
        return $this->groupeTache;
    }

 

    /**
     * Set lieu
     *
     * @param PHPM\Bundle\Entity\Lieu $lieu
     */
    public function setLieu(\PHPM\Bundle\Entity\Lieu $lieu)
    {
        $this->lieu = $lieu;
    }

    /**
     * Get lieu
     *
     * @return PHPM\Bundle\Entity\Lieu 
     */
    public function getLieu()
    {
        return $this->lieu;
    }



    /**
     * Add commentaires
     *
     * @param PHPM\Bundle\Entity\Commentaire $commentaires
     */
    public function addCommentaire(\PHPM\Bundle\Entity\Commentaire $commentaires)
    {
        $this->commentaires[] = $commentaires;
    }

    /**
     * Get commentaires
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

  

    /**
     * Add besoinsMateriel
     *
     * @param PHPM\Bundle\Entity\BesoinMateriel $besoinsMateriel
     */
    public function addBesoinMateriel(\PHPM\Bundle\Entity\BesoinMateriel $besoinsMateriel)
    {
        $this->besoinsMateriel[] = $besoinsMateriel;
    }

    /**
     * Get besoinsMateriel
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getBesoinsMateriel()
    {
        return $this->besoinsMateriel;
    }
    
    public function getMateriel()
    {
//         $em = $this->getDoctrine()->getEntityManager();
//         $data = $em->createQuery('SELECT b,m FROM PHPMBundle:BesoinMateriel b JOIN b.materiel m JOIN b.tache t WHERE t.id = 1')->getResult();
     
        
        $materiel = array();
        
        
        $bm = $this->besoinsMateriel;
        
        if(isset($bm)){
        
        foreach($this->besoinsMateriel as $bm){
            $m= $bm->getMateriel();
            
            if($m->getType()==0){
            $q=($bm->getQuantite()==1);
            }else{
                $q=$bm->getQuantite();
            }
            
            $materiel[$m->getCategorie()][$m->getId()]=$q;
        
            }
        
           
        return  $materiel;
        
        }
        return array();
        
    }
    

    
    

    /**
     * Set materielSupplementaire
     *
     * @param text $materielSupplementaire
     */
    public function setMaterielSupplementaire($materielSupplementaire)
    {
        $this->materielSupplementaire = $materielSupplementaire;
    }

    /**
     * Get materielSupplementaire
     *
     * @return text 
     */
    public function getMaterielSupplementaire()
    {
        return $this->materielSupplementaire;
    }
}