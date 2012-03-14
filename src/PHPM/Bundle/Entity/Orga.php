<?php

namespace PHPM\Bundle\Entity;

use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * PHPM\Bundle\Entity\Orga
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\OrgaRepository")
 * @UniqueEntity(fields={"email","telephone"})
 */
class Orga implements UserInterface
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
     * @var string $prenom
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $prenom;
    
    /**
    * @var string $surnom
    *
    * @ORM\Column(name="surnom", type="string", length=255, nullable=true)
    */
    protected $surnom;

    
    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/0[67][0-9]{8}/",
     *     message="Veuillez renseigner un numéro de portable valide."
     * )
     */
    protected $telephone;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255, unique=true)
     * 
     * @Assert\Email(     * 
     *     checkMX = true
     * )
     */
    protected $email;

    /**
     * @var date $dateDeNaissance
     *
     * @ORM\Column(name="dateDeNaissance", type="date", nullable=true)
     * 
     * @Assert\Date()
     */
    protected $dateDeNaissance;

    /**
     * @var string $departement
     *
     * @ORM\Column(name="departement", type="string", length=255, nullable=true)
     */
    protected $departement;

//     /**
//      * @var string $equipe
//      *
//      * @ORM\Column(name="equipe", type="string", length=255, nullable=true)
//      */
//     protected $equipe;
    
    
    
    /**
     * @var text $commentaire
     *
     * @ORM\Column(name="commentaire", type="text", nullable=true)
     */
    protected $commentaire;

    /**
     * @var smallint $permis
     * @Assert\Choice(choices = {"0", "1", "2"})
     * @ORM\Column(name="permis", type="smallint")
     */
    protected $permis;
    


    /**
    * @ORM\ManyToOne(targetEntity="Confiance", inversedBy="orgas")
    * @ORM\JoinColumn(name="confiance_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
    * @Assert\Valid
    */
    protected $confiance;
    
    /**
    * @ORM\OneToMany(targetEntity="Tache", mappedBy="responsable")
    */
    protected $tachesResponsable;
    
    /**
    * @ORM\OneToMany(targetEntity="Equipe", mappedBy="responsable")
    */
    protected $equipesResponsable;
    
    /**
     * @ORM\OneToMany(targetEntity="Disponibilite", mappedBy="orga", cascade={"persist", "remove"})
     */
    protected $disponibilites;
    
    /**
    * @var smallint $statut
    * @Assert\Choice(choices = {"0", "1"})
    * @ORM\Column(name="statut", type="smallint")
    */
    protected $statut;
    
    
    /**
     * @ORM\ManyToMany(targetEntity="DisponibiliteInscription", inversedBy="orgas")
     * @ORM\JoinTable(name="orga_disponibiliteinscription",
     *      joinColumns={@ORM\JoinColumn(name="orga_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="disponibiliteinscription_id", referencedColumnName="id")}
     *      )
     */
    protected $disponibilitesInscription;
    
    /**
     * @ORM\Column(type="boolean", name="is_admin",nullable=true)
     */
    protected $isAdmin;
    
    /**
     * @ORM\ManyToOne(targetEntity="Equipe", inversedBy="orgas")
     * @ORM\JoinColumn(name="equipe_id", referencedColumnName="id",onDelete="SET NULL", onUpdate="CASCADE")
     * @Assert\Valid
     */
    protected $equipe;
    
    /**
     * @ORM\OneToMany(targetEntity="Commentaire", mappedBy="auteur")
     */
    protected $commentaires;
    
    
    public function __toString()
    {
        return $this->prenom." ".$this->nom;
        
    }
    
    public function toArray($developCreneaux = NULL)
    {
    	$a = array();
    	if(isset($developCreneaux))
    	foreach ($this->getDisponibilites() as $entity){
    		$a[$entity->getId()] = $entity->toArray(TRUE);
    	}
    	 
    	 
    	return array(
    		"id" => $this->getId(),
    	    "importid" => $this->getImportId(),
    		"statut" => $this->getStatut(),
        	"nom" => $this->getNom(),
        	"prenom" => $this->getPrenom(),
    		"surnom" => $this->getSurnom(),
    		"telephone" => $this->getTelephone(),
    		"email" => $this->getEmail(),
    		"dateDeNaissance" => $this->getDateDeNaissance(),
    		"departement" => $this->getDepartement(),
    		"commentaire" => $this->getCommentaire(),
    		"permis"=>$this->getPermis(),
        	"confiance" => $this->getConfiance()->toArray(),
        	"disponibilites" => $a);
    	 
    }
    
    public function toExportArray()
    {
    	$a = array();
    	
    		foreach ($this->getDisponibilites() as $entity){
    		$a[$entity->getId()] = $entity->toSimpleArray();
    		}
    	
    
    
    	return array(
    			"id" => $this->getId(),
    			"statut" => $this->getStatut(),
    			"nom" => $this->getNom(),
    			"prenom" => $this->getPrenom(),
    			"surnom" => $this->getSurnom(),
    			"telephone" => $this->getTelephone(),
    			"email" => $this->getEmail(),
    			"dateDeNaissance" => $this->getDateDeNaissance()->format('Y-m-d'),
    			"departement" => $this->getDepartement(),
    			"commentaire" => $this->getCommentaire(),
    			"permis"=>$this->getPermis(),
    			"confiance" => $this->getConfiance()->getId(),
    			"disponibilites" => $a);
    
    }


    

    

    public function getRoles()
    {
    	return array('ROLE_ADMIN');
    }
    
    public function equals(UserInterface $user)
    {
    	return $user->getEmail() === $this->email;
    }
    
    public function eraseCredentials()
    {
    }
    

    
    public function getSalt()
    {
    	return "";
    }
    
    public function getPassword()
    {
    	return "";
    }
    
    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->email;
    }
    
    
    public function serialize()
    {
        return serialize($this->email);
    }
    
    public function unserialize($data)
    {
        $this->email = unserialize($data);
    }

    public function __construct()
    {
        $this->tachesResponsable = new \Doctrine\Common\Collections\ArrayCollection();
    $this->equipesResponsable = new \Doctrine\Common\Collections\ArrayCollection();
    $this->disponibilites = new \Doctrine\Common\Collections\ArrayCollection();
    $this->disponibilitesInscription = new \Doctrine\Common\Collections\ArrayCollection();
    $this->commentaires = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set prenom
     *
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set surnom
     *
     * @param string $surnom
     */
    public function setSurnom($surnom)
    {
        $this->surnom = $surnom;
    }

    /**
     * Get surnom
     *
     * @return string 
     */
    public function getSurnom()
    {
        return $this->surnom;
    }

    /**
     * Set telephone
     *
     * @param string $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * Get telephone
     *
     * @return string 
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dateDeNaissance
     *
     * @param date $dateDeNaissance
     */
    public function setDateDeNaissance($dateDeNaissance)
    {
        $this->dateDeNaissance = $dateDeNaissance;
    }

    /**
     * Get dateDeNaissance
     *
     * @return date 
     */
    public function getDateDeNaissance()
    {
        return $this->dateDeNaissance;
    }

    /**
     * Set departement
     *
     * @param string $departement
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;
    }

    /**
     * Get departement
     *
     * @return string 
     */
    public function getDepartement()
    {
        return $this->departement;
    }

    /**
     * Set commentaire
     *
     * @param text $commentaire
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;
    }

    /**
     * Get commentaire
     *
     * @return text 
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set permis
     *
     * @param smallint $permis
     */
    public function setPermis($permis)
    {
        $this->permis = $permis;
    }

    /**
     * Get permis
     *
     * @return smallint 
     */
    public function getPermis()
    {
        return $this->permis;
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
     * Set isAdmin
     *
     * @param boolean $isAdmin
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * Get isAdmin
     *
     * @return boolean 
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
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
     * Add tachesResponsable
     *
     * @param PHPM\Bundle\Entity\Tache $tachesResponsable
     */
    public function addTache(\PHPM\Bundle\Entity\Tache $tachesResponsable)
    {
        $this->tachesResponsable[] = $tachesResponsable;
    }

    /**
     * Get tachesResponsable
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTachesResponsable()
    {
        return $this->tachesResponsable;
    }

    /**
     * Add equipesResponsable
     *
     * @param PHPM\Bundle\Entity\Equipe $equipesResponsable
     */
    public function addEquipe(\PHPM\Bundle\Entity\Equipe $equipesResponsable)
    {
        $this->equipesResponsable[] = $equipesResponsable;
    }

    /**
     * Get equipesResponsable
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getEquipesResponsable()
    {
        return $this->equipesResponsable;
    }

    /**
     * Add disponibilites
     *
     * @param PHPM\Bundle\Entity\Disponibilite $disponibilites
     */
    public function addDisponibilite(\PHPM\Bundle\Entity\Disponibilite $disponibilites)
    {
        $this->disponibilites[] = $disponibilites;
    }

    /**
     * Get disponibilites
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getDisponibilites()
    {
        return $this->disponibilites;
    }

    /**
     * Add disponibilitesInscription
     *
     * @param PHPM\Bundle\Entity\DisponibiliteInscription $disponibilitesInscription
     */
    public function addDisponibiliteInscription(\PHPM\Bundle\Entity\DisponibiliteInscription $disponibilitesInscription)
    {
        $this->disponibilitesInscription[] = $disponibilitesInscription;
    }

    /**
     * Get disponibilitesInscription
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getDisponibilitesInscription()
    {
        return $this->disponibilitesInscription;
    }

    /**
     * Set equipe
     *
     * @param PHPM\Bundle\Entity\Equipe $equipe
     */
    public function setEquipe(\PHPM\Bundle\Entity\Equipe $equipe)
    {
        $this->equipe = $equipe;
    }

    /**
     * Get equipe
     *
     * @return PHPM\Bundle\Entity\Equipe 
     */
    public function getEquipe()
    {
        return $this->equipe;
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
}