<?php 
namespace PHPM\Bundle\Extension;

class ConfigExtension extends \Twig_Extension {

    protected $doctrine;
    protected $configs;
    
    public function __construct($doctrine) {
        
        $this->doctrine = $doctrine;
        

        $this->configs = $this->doctrine->getEntityManager()->getRepository('PHPMBundle:Config')->findAllAsArray();        
  
        
    }
    
    
    public function getFilters() {
        return array(
            'permis'     => new \Twig_Filter_Method($this, 'permis'),
        	'statutDI'     => new \Twig_Filter_Method($this, 'statutDI')
 
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

    public function getValue($field)
    {
        return $this->configs[$field];
    }
    
    public function getName()
    {
        return 'phpm_config_extension';
    }

}

?>