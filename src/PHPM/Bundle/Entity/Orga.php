<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\Orga
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\OrgaRepository")
 */
class Orga
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
     * @var string $prenom
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;
    
    /**
    * @var string $surnom
    *
    * @ORM\Column(name="surnom", type="string", length=255)
    */
    private $surnom;

    /**
     * @var string $telephone
     *
     * @ORM\Column(name="telephone", type="string", length=255)
     */
    private $telephone;

    /**
     * @var string $email
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

    /**
     * @var date $dateDeNaissance
     *
     * @ORM\Column(name="dateDeNaissance", type="date")
     */
    private $dateDeNaissance;

    /**
     * @var string $departement
     *
     * @ORM\Column(name="departement", type="string", length=255)
     */
    private $departement;

    /**
     * @var text $commentaire
     *
     * @ORM\Column(name="commentaire", type="text")
     */
    private $commentaire;

    /**
     * @var boolean $permisB
     *
     * @ORM\Column(name="permisB", type="boolean")
     */
    private $permisB;

    /**
     * @var boolean $permisB2ans
     *
     * @ORM\Column(name="permisB2ans", type="boolean")
     */
    private $permisB2ans;
    
    /**
    * @ORM\ManyToOne(targetEntity="Confiance", inversedBy="orgas")
    * @ORM\JoinColumn(name="confiance_id", referencedColumnName="id")
    */
    protected $confiance;
    
    /**
    * @ORM\OneToMany(targetEntity="Disponibilite", mappedBy="orga")
    */
    protected $disponibilites;
    
    


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
     * Set permisB
     *
     * @param boolean $permisB
     */
    public function setPermisB($permisB)
    {
        $this->permisB = $permisB;
    }

    /**
     * Get permisB
     *
     * @return boolean 
     */
    public function getPermisB()
    {
        return $this->permisB;
    }

    /**
     * Set permisB2ans
     *
     * @param boolean $permisB2ans
     */
    public function setPermisB2ans($permisB2ans)
    {
        $this->permisB2ans = $permisB2ans;
    }

    /**
     * Get permisB2ans
     *
     * @return boolean 
     */
    public function getPermisB2ans()
    {
        return $this->permisB2ans;
    }
    public function __construct()
    {
        $this->disponibilites = new \Doctrine\Common\Collections\ArrayCollection();
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
    
    public function __toString()
    {
    	return $this->getPrenom()." ".$this->getNom();
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
    
    public function toArray()
    {
    	foreach ($this->getDisponibilites() as $entity){
    		$a[$entity->getId()] = $entity->toArray();
    	}
    	 
    	 
    	return array(
        	"nom" => $this->getNom(),
        	"prenom" => $this->getPrenom(),
    		"surnom" => $this->getSurnom(),
    		"telephone" => $this->getTelephone(),
    		"email" => $this->getEmail(),
    		"dateDeNaissance" => $this->getDateDeNaissance(),
    		"departement" => $this->getDepartement(),
    		"commentaire" => $this->getCommentaire(),
        	"confiance" => $this->getConfiance()->toArray(),
        	"disponibilites" => $a);
    	 
    }
}