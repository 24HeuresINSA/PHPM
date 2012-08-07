<?php

namespace AssoMaker\PHPMBundle\Entity;


/**
 * AssoMaker\PHPMBundle\Entity\Animlinker
 */
 
class AnimLinker
{
    protected $dbpath;
    protected $edition;
   
    public function __construct($dbpath, $edition)
    {
        $this->dbpath = $dbpath;
        $this->edition = $edition;
    }
    
    public function getAnimsArray()
    {
    	$anims = array();
		
		// si le clef n'est pas configurée, on ne va pas chercher les anims
        if ($this->dbpath != '') {
	        $dbhandle = new \SQLite3($this->dbpath);
	        
	        if (!$dbhandle) die ($error);
			
	        $results = $dbhandle->query("SELECT id,numero,nom FROM anims WHERE edition=$this->edition");
			
	        while ($row = $results->fetchArray()) {
	            $anims[$row['id']]=($row['nom']);
	        }
		}
        
        return $anims;
    }
}