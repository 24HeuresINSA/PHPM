<?php

namespace AssoMaker\PHPMBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Sonata\IntlBundle\Templating\Helper;

/**
 * AssoMaker\PHPMBundle\Entity\BesoinMateriel
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\PHPMBundle\Entity\BesoinMaterielRepository")
 *
 */
class BesoinMateriel {

    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tache", inversedBy="besoinsMateriel")
     * @ORM\JoinColumn(name="tache_id", referencedColumnName="id",onDelete="CASCADE")
     * @Assert\Valid
     */
    protected $tache;

    /**
     * @ORM\ManyToOne(targetEntity="Materiel", inversedBy="besoinsMateriel")
     * @ORM\JoinColumn(name="materiel_id", referencedColumnName="id",onDelete="CASCADE")
     * @Assert\Valid
     */
    protected $materiel;

    /**
     * @var integer $quantite
     *
     * @ORM\Column(name="quantite", type="integer")
     *
     */
    protected $quantite;

    /**
     *
     *
     * @ORM\Column(type="string")
     *
     */
    protected $commentaireLog = 'non';

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     */
    public function setQuantite($quantite) {
        $this->quantite = $quantite;
        $this->invalidateComLog();
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite() {
        return $this->quantite;
    }

    /**
     * Set tache
     *
     * @param AssoMaker\PHPMBundle\Entity\Tache $tache
     */
    public function setTache(\AssoMaker\PHPMBundle\Entity\Tache $tache) {
        $this->tache = $tache;
    }

    /**
     * Get tache
     *
     * @return AssoMaker\PHPMBundle\Entity\Tache
     */
    public function getTache() {
        return $this->tache;
    }

    /**
     * Set materiel
     *
     * @param AssoMaker\PHPMBundle\Entity\Materiel $materiel
     */
    public function setMateriel(\AssoMaker\PHPMBundle\Entity\Materiel $materiel) {
        $this->materiel = $materiel;
    }

    /**
     * Get materiel
     *
     * @return AssoMaker\PHPMBundle\Entity\Materiel
     */
    public function getMateriel() {
        return $this->materiel;
    }

    /**
     * Set commentaireLog
     *
     * @param string $commentaireLog
     * @return BesoinMateriel
     */
    public function setCommentaireLog($commentaireLog) {
        $this->commentaireLog = $commentaireLog;

        return $this;
    }

    /**
     * Get commentaireLog
     *
     * @return string
     */
    public function getCommentaireLog() {
        return $this->commentaireLog;
    }

    public function invalidateComLog() {
        if (strncmp($this->getCommentaireLog(), 'non', 3))
            $this->setCommentaireLog('non (' . $this->getCommentaireLog() . ')');
    }

}

