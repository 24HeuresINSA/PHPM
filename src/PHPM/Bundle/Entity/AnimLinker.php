<?php

namespace PHPM\Bundle\Entity;


/**
 * PHPM\Bundle\Entity\Animlinker
 */
 
class AnimLinker
{
    protected  $dbpath;
   
    public function __construct($dbpath){
        $this->dbpath=$dbpath;
        
    }
    
    public function getAnimsArray()
    {
        
        $dbhandle = new \SQLite3($this->dbpath);
        
        $anims = array();
        if (!$dbhandle) die ($error);
        $results = $dbhandle->query('SELECT id,numero,nom FROM anims');
        while ($row = $results->fetchArray()) {
            $anims[$row['id']]=($row['nom']);
        }
        
        return $anims;
    }
}