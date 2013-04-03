<?php

namespace AssoMaker\PHPMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AssoMaker\PHPMBundle\Entity\Tache
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PHPMBundle\Entity\TacheRepository")
 */
class Tache {

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
     * @ORM\Column(name="consignes", type="text", nullable=true)
     * @Assert\NotBlank()
     */
    protected $consignes;

    /**
     * @var text $materielSupplementaire
     *
     * @ORM\Column(name="materielSupplementaire", type="text", nullable=true)
     *
     */
    protected $materielSupplementaire;

    /**
     * @var smallint $permisNecessaire
     *
     * @ORM\Column(name="permisNecessaire", type="smallint")
     *
     */
    protected $permisNecessaire;

    /**
     * @var string $lieu
     *
     * @ORM\Column(name="lieu", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $lieu;


//     /**
//      * @ORM\ManyToOne(targetEntity="Lieu", inversedBy="taches")
//      * @ORM\JoinColumn(name="lieu_id", referencedColumnName="id",onDelete="SET NULL")
//      * @Assert\Valid
//      */
//     protected $lieu;

    /**
     * @ORM\ManyToOne(targetEntity="AssoMaker\BaseBundle\Entity\Orga", inversedBy="tachesResponsable")
     * @ORM\JoinColumn(name="responsable_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $responsable;

//     /**
//     * @ORM\ManyToOne(targetEntity="Categorie", inversedBy="taches")
//     * @ORM\JoinColumn(name="categorie_id", referencedColumnName="id",onDelete="SET NULL")
//     * @Assert\Valid
//     */
//     protected $categorie;

    /**
     * @ORM\OneToMany(targetEntity="PlageHoraire", mappedBy="tache", indexBy="id",orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $plagesHoraire;

    /**
     * @ORM\ManyToOne(targetEntity="GroupeTache", inversedBy="taches")
     * @ORM\JoinColumn(name="groupetache_id", referencedColumnName="id",onDelete="CASCADE")
     * @Assert\Valid
     */
    protected $groupeTache;

    /**
     * @var smallint $statut
     * @Assert\Choice(choices = {"0", "1", "2", "3", "-1"})
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
    public function getId() {
        return $this->id;
    }

    /**
     * Set nom
     *
     * @param string $nom
     */
    public function setNom($nom) {
        $this->nom = $nom;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    public function __construct() {
        $this->plagesHoraire = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set confiance
     *
     * @param AssoMaker\BaseBundle\Entity\Confiance $confiance
     */
    public function setConfiance(\AssoMaker\BaseBundle\Entity\Confiance $confiance) {
        $this->confiance = $confiance;
    }

    /**
     * Get confiance
     *
     * @return AssoMaker\BaseBundle\Entity\Confiance
     */
    public function getConfiance() {
        return $this->confiance;
    }

    /**
     * Add plagesHoraire
     *
     * @param AssoMaker\PHPMBundle\Entity\PlageHoraire $plagesHoraire
     */
    public function addPlageHoraire(\AssoMaker\PHPMBundle\Entity\PlageHoraire $plageHoraire) {
        $plageHoraire->setTache($this);
        $this->plagesHoraire[] = $plageHoraire;
    }

    /**
     * Set plagesHoraire
     *
     * @param AssoMaker\PHPMBundle\Entity\PlageHoraire $plagesHoraire
     */
    public function setPlagesHoraire($plagesHoraire) {
        foreach ($plagesHoraire as $pl)
            $pl->setTache($this);
        $this->plagesHoraire = $plagesHoraire;
    }

    /**
     * Get plagesHoraire
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getPlagesHoraire() {
        return $this->plagesHoraire;
    }

    public function __toString() {
        return $this->getId() . "- " . $this->getNom();
    }

    public function toArray($developCreneaux = NULL) {
        $a = array();
        if (isset($developCreneaux))
            foreach ($this->getPlagesHoraire() as $entity) {
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

    public function toSearchArray() {
        return array(
            "type" => "tache",
            "id" => $this->getId(),
            "nom" => $this->getNom());
    }

    public function toArrayOrgaWebService($developCreneaux = NULL) {

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
    public function setPermisNecessaire($permisNecessaire) {
        $this->permisNecessaire = $permisNecessaire;
    }

    /**
     * Get permisNecessaire
     *
     * @return smallint
     */
    public function getPermisNecessaire() {
        return $this->permisNecessaire;
    }

    /**
     * Set consignes
     *
     * @param text $consignes
     */
    public function setConsignes($consignes) {
        $this->consignes = $consignes;
    }

    /**
     * Get consignes
     *
     * @return text
     */
    public function getConsignes() {
        return $this->consignes;
    }

    /**
     * Set importId
     *
     * @param integer $importId
     */
    public function setImportId($importId) {
        $this->importId = $importId;
    }

    /**
     * Get importId
     *
     * @return integer
     */
    public function getImportId() {
        return $this->importId;
    }

    /**
     * Set responsable
     *
     * @param AssoMaker\BaseBundle\Entity\Orga $responsable
     */
    public function setResponsable(\AssoMaker\BaseBundle\Entity\Orga $responsable) {
        $this->responsable = $responsable;
    }

    /**
     * Get responsable
     *
     * @return AssoMaker\BaseBundle\Entity\Orga
     */
    public function getResponsable() {
        return $this->responsable;
    }

    /**
     * Set statut
     *
     * @param smallint $statut
     */
    public function setStatut($statut) {
        $this->statut = $statut;
    }

    /**
     * Get statut
     *
     * @return smallint
     */
    public function getStatut() {
        return $this->statut;
    }

    /**
     * Set groupeTache
     *
     * @param AssoMaker\PHPMBundle\Entity\GroupeTache $groupeTache
     */
    public function setGroupeTache(\AssoMaker\PHPMBundle\Entity\GroupeTache $groupeTache) {
        $this->groupeTache = $groupeTache;
    }

    /**
     * Get groupeTache
     *
     * @return AssoMaker\PHPMBundle\Entity\GroupeTache
     */
    public function getGroupeTache() {
        return $this->groupeTache;
    }

    /**
     * Add commentaires
     *
     * @param AssoMaker\PHPMBundle\Entity\Commentaire $commentaires
     */
    public function addCommentaire(\AssoMaker\PHPMBundle\Entity\Commentaire $commentaires) {
        $this->commentaires[] = $commentaires;
    }

    /**
     * Get commentaires
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCommentaires() {
        return $this->commentaires;
    }

    /**
     * Add besoinsMateriel
     *
     * @param AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel
     */
    public function addBesoinMateriel(\AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel) {
        $this->besoinsMateriel[] = $besoinsMateriel;
    }

    /**
     * Get besoinsMateriel
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getBesoinsMateriel() {
        return $this->besoinsMateriel;
    }

    public function getMateriel() {
//         $em = $this->getDoctrine()->getEntityManager();
//         $data = $em->createQuery('SELECT b,m FROM AssoMakerPHPMBundle:BesoinMateriel b JOIN b.materiel m JOIN b.tache t WHERE t.id = 1')->getResult();


        $materiel = array();


        $bm = $this->besoinsMateriel;

        if (isset($bm)) {

            foreach ($this->besoinsMateriel as $bm) {
                $m = $bm->getMateriel();

                if ($m->getType() == 0) {
                    $q = ($bm->getQuantite() == 1);
                } else {
                    $q = $bm->getQuantite();
                }

                $materiel[$m->getCategorie()][$m->getId()] = $q;
            }


            return $materiel;
        }
        return array();
    }

    /**
     * Set materielSupplementaire
     *
     * @param text $materielSupplementaire
     */
    public function setMaterielSupplementaire($materielSupplementaire) {
        $this->materielSupplementaire = $materielSupplementaire;
    }

    /**
     * Get materielSupplementaire
     *
     * @return text
     */
    public function getMaterielSupplementaire() {
        return $this->materielSupplementaire;
    }

    /**
     * Set lieu
     *
     * @param string $lieu
     */
    public function setLieu($lieu) {
        $this->lieu = $lieu;
    }

    /**
     * Get lieu
     *
     * @return string
     */
    public function getLieu() {
        return $this->lieu;
    }

    public function removeAllCreneaux() {
        foreach ($this->getPlagesHoraire() as $ph) {
            foreach ($ph->getCreneaux() as $creneau) {
                $creneau->setPlageHoraire(null);
            }
        }
    }

    /**
     * Add plagesHoraire
     *
     * @param \AssoMaker\PHPMBundle\Entity\PlageHoraire $plagesHoraire
     * @return Tache
     */
    public function addPlagesHoraire(\AssoMaker\PHPMBundle\Entity\PlageHoraire $plagesHoraire) {
        $this->plagesHoraire[] = $plagesHoraire;
        $this->invalidBesoinsMateriel();

        return $this;
    }

    /**
     * Remove plagesHoraire
     *
     * @param \AssoMaker\PHPMBundle\Entity\PlageHoraire $plagesHoraire
     */
    public function removePlagesHoraire(\AssoMaker\PHPMBundle\Entity\PlageHoraire $plagesHoraire) {
        $this->plagesHoraire->removeElement($plagesHoraire);
        $this->invalidBesoinsMateriel();
    }

    /**
     * Remove commentaires
     *
     * @param \AssoMaker\PHPMBundle\Entity\Commentaire $commentaires
     */
    public function removeCommentaire(\AssoMaker\PHPMBundle\Entity\Commentaire $commentaires) {
        $this->commentaires->removeElement($commentaires);
    }

    /**
     * Add besoinsMateriel
     *
     * @param \AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel
     * @return Tache
     */
    public function addBesoinsMateriel(\AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel) {
        $this->besoinsMateriel[] = $besoinsMateriel;

        return $this;
    }

    /**
     * Remove besoinsMateriel
     *
     * @param \AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel
     */
    public function removeBesoinsMateriel(\AssoMaker\PHPMBundle\Entity\BesoinMateriel $besoinsMateriel) {
        $this->besoinsMateriel->removeElement($besoinsMateriel);
    }

    public function invalidBesoinsMateriel() {
        foreach ($this->besoinsMateriel as $b) {
            $b->invalidateComLog();
        }
    }

}

