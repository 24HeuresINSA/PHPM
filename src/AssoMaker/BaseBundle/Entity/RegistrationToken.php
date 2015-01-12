<?php

namespace AssoMaker\BaseBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * RegistrationToken
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AssoMaker\BaseBundle\Entity\RegistrationTokenRepository")
 */
class RegistrationToken
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Equipe", inversedBy="tokens")
     * @ORM\JoinColumn(name="equipe_id", referencedColumnName="id",onDelete="SET NULL")
     * @Assert\Valid
     */
    private $equipe;

    /**
     * @var id
     *
     * @ORM\Column(name="count", type="integer", nullable=false)
     */
    private $count;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=255, nullable=false, unique=true)
     */
    private $token;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;


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
     * Set token
     *
     * @param string $token
     * @return RegistrationToken
     */
    public function setToken($token)
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Get token
     *
     * @return string 
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Génère une suite aléatoire de caractères.
     *
     * @param int $length La longueur de la clef
     * @return string Une clef de la longeur voulue
     */
    private function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyz-_")éç")é&ç*-+/¶Êøå€±¬øABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return RegistrationToken
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set equipe
     *
     * @param \AssoMaker\BaseBundle\Entity\Equipe $equipe
     * @return RegistrationToken
     */
    public function setEquipe(\AssoMaker\BaseBundle\Entity\Equipe $equipe = null)
    {
        $this->equipe = $equipe;
        $this->token = md5($this->generateRandomString(20));
        return $this;
    }

    /**
     * Get equipe
     *
     * @return \AssoMaker\BaseBundle\Entity\Equipe 
     */
    public function getEquipe()
    {
        return $this->equipe;
    }

    /**
     * @return id
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param id $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }

    /**
     * Enregistre une utilisation
     */
    public function oneUse(){
        $this->count--;
    }
}
