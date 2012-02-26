<?php

namespace PHPM\Bundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * PHPM\Bundle\Entity\Config
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="PHPM\Bundle\Entity\ConfigRepository")
 * @UniqueEntity("field")
 */
class Config
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string $field
     *
     * @ORM\Column(name="field", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     */
    protected $field;
    
    /**
     * @var string $label
     *
     * @ORM\Column(name="label", type="string", length=255)
     * @Assert\NotBlank()
     */
    protected $label;

    /**
     * @var text $value
     *
     * @ORM\Column(name="value", type="text")
     * @Assert\NotBlank()
     */
    protected $value;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set field
     *
     * @param string $field
     */
    public function setField($field)
    {
        $this->field = $field;
    }

    /**
     * Get field
     *
     * @return string 
     */
    public function getField()
    {
        return $this->field;
    }

    
    public function toArray()
    {
    	return array("id" => $this->getId(),"field" => $this->getField(),"value" => $this->getValue());
    }
    
    public function __toString()
    {
    	return "";
    }

    /**
     * Set value
     *
     * @param text $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return text 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set label
     *
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * Get label
     *
     * @return string 
     */
    public function getLabel()
    {
        return $this->label;
    }
}