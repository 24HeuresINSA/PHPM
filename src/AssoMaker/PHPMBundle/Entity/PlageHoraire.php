<?php

namespace AssoMaker\PHPMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AssoMaker\PHPMBundle\Validator\DebutAvantFin;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use AssoMaker\PHPMBundle\Validator\QuartHeure;
use AssoMaker\PHPMBundle\Validator\Recoupe;

/**
 * AssoMaker\PHPMBundle\Entity\PlageHoraire
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PHPMBundle\Entity\PlageHoraireRepository")
 * @DebutAvantFin()
 */
class PlageHoraire {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var datetime $debut
     *
     * @ORM\Column(name="debut", type="datetime")

     * @QuartHeure()
     */
    protected $debut;

    /**
     * @var datetime $fin
     *
     * @ORM\Column(name="fin", type="datetime")
     * @QuartHeure()
     */
    protected $fin;

    /**
     * @var smallint $dureeCreneau
     *
     * @ORM\Column(name="dureeCreneau", type="smallint")
     * @Assert\Range(min = "0")
     * @QuartHeure()
     */
    protected $dureeCreneau;

    /**
     * @var bool $creneauUnique
     *
     * @ORM\Column(name="creneauUnique", type="boolean", nullable=true)
     */
    protected $creneauUnique;

    /**
     * @var smallint $recoupementCreneau
     *
     * @ORM\Column(name="recoupementCreneau", type="smallint")
     * @Assert\Range(min = "0")
     * @QuartHeure()
     */
    protected $recoupementCreneau;

    /**
     * @var bool $respNecessaire
     *
     * @ORM\Column(name="respNecessaire", type="boolean", nullable=true)
     */
    protected $respNecessaire;

    /**
     * @ORM\OneToMany(targetEntity="Creneau", mappedBy="plageHoraire", orphanRemoval=true)
     */
    protected $creneaux;

    /**
     * @ORM\OneToMany(targetEntity="\AssoMaker\PHPMBundle\Entity\BesoinOrga", mappedBy="plageHoraire",orphanRemoval=true, cascade={"persist", "remove"})
     */
    protected $besoinsOrga;

    /**
     * @ORM\ManyToOne(targetEntity="Tache", inversedBy="plagesHoraire")
     * @ORM\JoinColumn(name="tache_id", referencedColumnName="id",onDelete="CASCADE")
     * @Assert\Valid
     */
    protected $tache;

    public function getDuree() {
        return ($this->getFin()->getTimestamp() - $this->getDebut()->getTimestamp());
    }

    public function __toString() {
        return $this->getTache()->__toString() . " - " . $this->getDebut()->format('D H:i') . " - " . $this->getFin()->format('D H:i');
    }

    public function toArray($developCreneaux = NULL) {

        $a = array();
        if (isset($developCreneaux))
            foreach ($this->getCreneaux() as $entity) {
                $a[$entity->getId()] = $entity->toArray();
            }

        return array(
            "id" => $this->getId(),
            "debut" => $this->getDebut(),
            "fin" => $this->getFin(),
            "duree" => $this->getDuree(),
            "nbOrgasNecessaires" => $this->getNbOrgasNecessaires(),
            "tache" => $this->getTache()->toArray(),
            "creneaux" => $a);
    }

    public function toSimpleArray() {
        return array("debut" => $this->getDebut(), "fin" => $this->getFin(), "duree" => $this->getDuree());
    }

    public function __construct() {
        $this->creneaux = new \Doctrine\Common\Collections\ArrayCollection();
        $this->besoinsOrga = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set debut
     *
     * @param datetime $debut
     */
    public function setDebut($debut) {
        $this->debut = $debut;
        $this->getTache()->invalidBesoinsMateriel();
    }

    /**
     * Get debut
     *
     * @return datetime
     */
    public function getDebut() {
        return $this->debut;
    }

    /**
     * Set fin
     *
     * @param datetime $fin
     */
    public function setFin($fin) {
        $this->fin = $fin;
        $this->getTache()->invalidBesoinsMateriel();
    }

    /**
     * Get fin
     *
     * @return datetime
     */
    public function getFin() {
        return $this->fin;
    }

    /**
     * Set dureeCreneau
     *
     * @param smallint $dureeCreneau
     */
    public function setDureeCreneau($dureeCreneau) {
        $this->dureeCreneau = $dureeCreneau;
    }

    /**
     * Get dureeCreneau
     *
     * @return smallint
     */
    public function getDureeCreneau() {
        return $this->dureeCreneau;
    }

    /**
     * Set recoupementCreneau
     *
     * @param smallint $recoupementCreneau
     */
    public function setRecoupementCreneau($recoupementCreneau) {
        $this->recoupementCreneau = $recoupementCreneau;
    }

    /**
     * Get recoupementCreneau
     *
     * @return smallint
     */
    public function getRecoupementCreneau() {
        return $this->recoupementCreneau;
    }

    /**
     * Set nbOrgasNecessaires
     *
     * @param smallint $nbOrgasNecessaires
     */
    public function setNbOrgasNecessaires($nbOrgasNecessaires) {
        $this->nbOrgasNecessaires = $nbOrgasNecessaires;
    }

    /**
     * Get nbOrgasNecessaires
     *
     * @return smallint
     */
    public function getNbOrgasNecessaires() {
        return $this->nbOrgasNecessaires;
    }

    /**
     * Set nbOrgasComNecessaires
     *
     * @param smallint $nbOrgasComNecessaires
     */
    public function setNbOrgasComNecessaires($nbOrgasComNecessaires) {
        $this->nbOrgasComNecessaires = $nbOrgasComNecessaires;
    }

    /**
     * Get nbOrgasComNecessaires
     *
     * @return smallint
     */
    public function getNbOrgasComNecessaires() {
        return $this->nbOrgasComNecessaires;
    }

    /**
     * Set respNecessaire
     *
     * @param boolean $respNecessaire
     */
    public function setRespNecessaire($respNecessaire) {
        $this->respNecessaire = $respNecessaire;
    }

    /**
     * Get respNecessaire
     *
     * @return boolean
     */
    public function getRespNecessaire() {
        return $this->respNecessaire;
    }

    /**
     * Add creneaux
     *
     * @param AssoMaker\PHPMBundle\Entity\Creneau $creneaux
     */
    public function addCreneau(\AssoMaker\PHPMBundle\Entity\Creneau $creneaux) {
        $this->creneaux[] = $creneaux;
    }

    /**
     * Get creneauxcascade={"persist"}
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getCreneaux() {
        return $this->creneaux;
    }

    /**
     * Add besoinsOrga
     *
     * @param AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrga
     */
    public function addBesoinOrga(\AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrga) {
        $this->besoinsOrga[] = $besoinsOrga;
    }

    /**
     * Get besoinsOrgaTotal
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getBesoinsOrgaTotal() {
        $count = $this->getRespNecessaire() * 1;

        foreach ($this->getBesoinsOrga() as $bo)
            if ($bo->getOrgaHint() == NULL) {
                $count+=$bo->getNbOrgasNecessaires();
            } else {
                $count+=1;
            }

        return $count;
    }

    /**
     * Get besoinsOrga
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getBesoinsOrga() {
        return $this->besoinsOrga;
    }

    /**
     * Set besoinsOrga
     *
     * @param \AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrga
     */
    public function setBesoinsOrga($besoinsOrga) {
        $a = $besoinsOrga->getValues();
        foreach($besoinsOrga as &$bo) {
            $besoin=$bo;
            if(is_array($bo)){ // Si les données sont sous forme de tableau, on crée les objets
                // new entity
                $besoin = new BesoinOrga();

                // now loop over the properties of each post array...
                foreach ($bo as $property => $value) {
                    // create a setter
                    $method = sprintf('set%s', ucwords($property)); // or you can cheat and omit ucwords() because PHP method calls are case insensitive
                    // use the method as a variable variable to set your value
                    $besoin->$method($value);
                }
            }
            $besoin->setPlageHoraire($this);
            $bo=$besoin;
            $this->besoinsOrga->add($bo);
        }
    }

    /**
     * Set tache
     *
     * @param \AssoMaker\PHPMBundle\Entity\Tache $tache
     */
    public function setTache(\AssoMaker\PHPMBundle\Entity\Tache $tache) {
        $this->tache = $tache;
    }

    /**
     * Get tache
     *
     * @return \AssoMaker\PHPMBundle\Entity\Tache
     */
    public function getTache() {
        return $this->tache;
    }

    /**
     * Set creneauUnique
     *
     * @param boolean $creneauUnique
     */
    public function setCreneauUnique($creneauUnique) {
        $this->creneauUnique = $creneauUnique;
    }

    /**
     * Get creneauUnique
     *
     * @return boolean
     */
    public function getCreneauUnique() {
        return $this->creneauUnique;
    }

    /**
     * Add creneaux
     *
     * @param \AssoMaker\PHPMBundle\Entity\Creneau $creneaux
     * @return PlageHoraire
     */
    public function addCreneaux(\AssoMaker\PHPMBundle\Entity\Creneau $creneaux) {
        $this->creneaux[] = $creneaux;

        return $this;
    }

    /**
     * Remove creneaux
     *
     * @param \AssoMaker\PHPMBundle\Entity\Creneau $creneaux
     */
    public function removeCreneaux(\AssoMaker\PHPMBundle\Entity\Creneau $creneaux) {
        $this->creneaux->removeElement($creneaux);
    }

    /**
     * Add besoinsOrga
     *
     * @param \AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrga
     * @return PlageHoraire
     */
    public function addBesoinsOrga(\AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrga) {
        $this->besoinsOrga[] = $besoinsOrga;

        return $this;
    }

    /**
     * Remove besoinsOrga
     *
     * @param \AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrga
     */
    public function removeBesoinsOrga(\AssoMaker\PHPMBundle\Entity\BesoinOrga $besoinsOrga) {
        $this->besoinsOrga->removeElement($besoinsOrga);
    }

}

