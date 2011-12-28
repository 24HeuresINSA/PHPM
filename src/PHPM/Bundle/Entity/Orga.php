<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * PHPM\Bundle\Entity\Orga
 */
class Orga
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $nom
     */
    private $nom;

    /**
     * @var string $prenom
     */
    private $prenom;

    /**
     * @var string $telephone
     */
    private $telephone;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var date $dateDeNaissance
     */
    private $dateDeNaissance;

    /**
     * @var string $departement
     */
    private $departement;

    /**
     * @var text $commentaire
     */
    private $commentaire;

    /**
     * @var boolean $permisB
     */
    private $permisB;

    /**
     * @var boolean $permisB2ans
     */
    private $permisB2ans;


    /**
    * @ORM\OneToMany(targetEntity="Disponibilite", mappedBy="orga")
    */
    protected $disponibilites;
    
    /**
    * @ORM\ManyToOne(targetEntity="Confiance", inversedBy="orga")
    * @ORM\JoinColumn(name="Confiance_id", referencedColumnName="id")
    */
    protected $confiance;
    
    
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
}