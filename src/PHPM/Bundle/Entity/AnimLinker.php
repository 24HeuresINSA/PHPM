<?php

namespace PHPM\Bundle\Entity;


/**
 * PHPM\Bundle\Entity\Animlinker
 */
 
class AnimLinker
{
    protected  $dbpath;
    protected $edition;
   
    public function __construct($dbpath, $edition){
        $this->dbpath=$dbpath;
        $this->edition=$edition;
        
    }
    
    public function getAnimsArray()
    {
        
        $dbhandle = new \SQLite3($this->dbpath);
        
        $anims = array();
        if (!$dbhandle) die ($error);
        $results = $dbhandle->query("SELECT id,numero,nom FROM anims WHERE edition=$this->edition");
        while ($row = $results->fetchArray()) {
            $anims[$row['id']]=($row['nom']);
        }
        
        return $anims;
    }
}