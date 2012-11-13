<?php 
namespace AssoMaker\BaseBundle\Extension;

class ConfigExtension extends \Twig_Extension {

    protected $doctrine;
    protected $configs;
    
    public function __construct($doctrine) {
        
        $this->doctrine = $doctrine;
        

        $this->configs = $this->doctrine->getEntityManager()->getRepository('AssoMakerPHPMBundle:Config')->findAllAsArray();        
  
        
    }
    
    
    public function getFilters() {
        return array(
            'permis'     => new \Twig_Filter_Method($this, 'permis'),
        	'statutDI'     => new \Twig_Filter_Method($this, 'statutDI'),
        	'phpm_crypt'     => new \Twig_Filter_Method($this, 'phpm_crypt'),
            'accessComptesPerso'     => new \Twig_Filter_Method($this, 'accessComptesPerso'),
            'accessTrombi'     => new \Twig_Filter_Method($this, 'accessTrombi')
 
        );
    }

    public function getGlobals()
    {
        return $this->configs;
    }
    public function permis($key) {
        $libelles = json_decode($this->configs['manifestation_permis_libelles'],true);
        return $libelles[$key];
    }
    public function statutDI($key) {
    	$libelles = array('0'=>'Verrouillé', '1'=>'Cochable Uniquement', '2'=>'Cochable/Décochable');
    	return $libelles[$key];
    }
    
    public function phpm_crypt($string) {
    	
    	return crypt($this->configs['phpm_secret_salt'].$string, 24);
    }
    
    public function accessComptesPerso($user) {
        return $this->configs['comptes_perso_actif'] && ($user->getEquipe()->getComptesPersoEnabled());
    }
    
    public function accessTrombi($user) {
        return $user->getEquipe()->getShowOnTrombi();
    }

    public function getValue($field)
    {
    	if(array_key_exists($field, $this->configs)){
        return $this->configs[$field];
    	}
    	
    	return null;
    	
    }
    
    public function getName()
    {
        return 'phpm_config_extension';
    }
    

}

?>