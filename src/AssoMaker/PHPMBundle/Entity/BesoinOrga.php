<?php

namespace AssoMaker\PHPMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use AssoMaker\PHPMBundle\Validator\DebutAvantFin;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use AssoMaker\PHPMBundle\Validator\QuartHeure;
use AssoMaker\PHPMBundle\Validator\Recoupe;

/**
 * AssoMaker\PHPMBundle\Entity\BesoinOrga
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PHPMBundle\Entity\BesoinOrgaRepository")
 *
 *
 */
class BesoinOrga {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="\AssoMaker\PHPMBundle\Entity\PlageHoraire",inversedBy="besoinsOrga")
     * @ORM\JoinColumn(name="plageHoraire_id", referencedColumnName="id",onDelete="CASCADE")
     * @Assert\Valid
     */
    protected $plageHoraire;

    /**
     * @ORM\ManyToOne(targetEntity="\AssoMaker\BaseBundle\Entity\Equipe")
     * @ORM\JoinColumn(name="equipe_id", referencedColumnName="id", nullable=false)
     * @Assert\Valid
     */
    protected $equipe;

    /**
     * @var smallint $nbOrgasNecessaires
     *
     * @ORM\Column(name="nbOrgasNecessaires", type="smallint", nullable=true)
     */
    protected $nbOrgasNecessaires;

    /**
     * @ORM\ManyToOne(targetEntity="\AssoMaker\BaseBundle\Entity\Orga", inversedBy="besoinsOrgaHint")
     * @ORM\JoinColumn(name="orgaHint_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    protected $orgaHint;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
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
     * Set plageHoraire
     *
     * @param AssoMaker\PHPMBundle\Entity\PlageHoraire $plageHoraire
     */
    public function setPlageHoraire(\AssoMaker\PHPMBundle\Entity\PlageHoraire $plageHoraire) {
        $this->plageHoraire = $plageHoraire;
    }

    /**
     * Get plageHoraire
     *
     * @return AssoMaker\PHPMBundle\Entity\PlageHoraire
     */
    public function getPlageHoraire() {
        return $this->plageHoraire;
    }

    /**
     * Set equipe
     *
     * @param AssoMaker\BaseBundle\Entity\Equipe $equipe
     */
    public function setEquipe($equipe) {
        $this->equipe = $equipe;
    }

    /**
     * Get equipe
     *
     * @return AssoMaker\BaseBundle\Entity\Equipe
     */
    public function getEquipe() {
        return $this->equipe;
    }

    /**
     * Set orgaHint
     *
     * @param AssoMaker\BaseBundle\Entity\Orga $orgaHint
     */
    public function setOrgaHint($orgaHint) {
        $this->orgaHint = $orgaHint;
    }

    /**
     * Get orgaHint
     *
     * @return AssoMaker\BaseBundle\Entity\Orga
     */
    public function getOrgaHint() {
        return $this->orgaHint;
    }

}

