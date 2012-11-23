<?php 
namespace AssoMaker\BaseBundle\Extension;

class StringsExtension extends \Twig_Extension {

   
    
    public function __construct($doctrine) {
        
    }
    
    
    public function getFilters() {
        return array(
            'telephone'     => new \Twig_Filter_Method($this, 'telephone'),
             'intlTelephone'     => new \Twig_Filter_Method($this, 'intlTelephone'),
        );
    }

   
    public function telephone($str) {
    	for ($i=2;$i<=11;$i+=3){
    	    $str = substr_replace($str, ' ', $i, 0);
    	}
        return $str;
    }
    
    public function intlTelephone($str) {
            $str = substr_replace($str, '+33', 0, 1);
        return $str;
    }   
    
    public function getName()
    {
        return 'phpm_config_strings';
    }

}

?>