<?php

namespace AssoMaker\BaseBundle\Extension;

use AssoMaker\BaseBundle\Entity\Orga;
use AssoMaker\PHPMBundle\Entity\DisponibiliteInscription;
use AssoMaker\PHPMBundle\Entity\LimiteInscription;

class UserExtension extends \Twig_Extension {

    protected $doctrine;
    protected $configs;

    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
        $this->configs = $this->doctrine->getManager()->getRepository('AssoMakerPHPMBundle:Config')->findAllAsArray();
    }

    /**
     * @param $orga Orga
     * @param $di DisponibiliteInscription
     * @return bool True if user can edit registration on this DI
     */
    public function canEditRegisterOnDI($orga,$di) {
        if($di->getDebut()->getTimestamp()-time()<0) return false;
        if($di->getStatut()==0) return false;
        else if($di->getStatut()==2) return true;
        if($orga->getDisponibilitesInscription()->contains($di)) return false;
        if($di->getLimitesInscriptions() == null) return true;
        if($di->getOrgas()==null) return true;
        $limit = $di->getLimitesInscriptions()->filter(function(LimiteInscription $limiteInscription)use($orga){
            if($limiteInscription->getConfiance()==null||$orga->getEquipe()==null||$orga->getEquipe()->getConfiance()==null) return false;
            return $limiteInscription->getConfiance()->getValeur() == $orga->getEquipe()->getConfiance()->getValeur();
        });
        if($limit->count()==0) return true;
        return $di->getOrgas()->filter(function(Orga $orgaAFiltrer)use($orga){
            if($orgaAFiltrer->getEquipe()==null||$orgaAFiltrer->getEquipe()->getConfiance()==null||$orga->getEquipe()==null||$orga->getEquipe()->getConfiance()==null) return false;
            return $orgaAFiltrer->getEquipe()->getConfiance()->getValeur() == $orga->getEquipe()->getConfiance()->getValeur();
        })->count() < $limit->first()->getMax();
    }

    public function getName() {
        return 'phpm_user_extension';
    }
    public function getFunctions()
{
    return array(
        'canEditRegisterOnDI' => new \Twig_Function_Method($this, 'canEditRegisterOnDI', array('is_safe' => array('html')))
    );
}

}

?>