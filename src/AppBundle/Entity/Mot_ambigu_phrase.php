<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mot_ambigu_phrase
 *
 * @ORM\Table(name="mot_ambigu_phrase")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Mot_ambigu_phraseRepository")
 */
class Mot_ambigu_phrase
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="valeur_mot_ambigu", type="string", length=32)
     */
    private $valeurMotAmbigu;

    /**
     * @var int
     *
     * @ORM\Column(name="id_mot_ambigu", type="integer", nullable=true)
     */
    private $idMotAmbigu;

    /**
     * @var int
     *
     * @ORM\Column(name="id_phrase", type="integer", nullable=true)
     */
    private $idPhrase;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set valeurMotAmbigu
     *
     * @param string $valeurMotAmbigu
     *
     * @return Mot_ambigu_phrase
     */
    public function setValeurMotAmbigu($valeurMotAmbigu)
    {
        $this->valeurMotAmbigu = $valeurMotAmbigu;

        return $this;
    }

    /**
     * Get valeurMotAmbigu
     *
     * @return string
     */
    public function getValeurMotAmbigu()
    {
        return $this->valeurMotAmbigu;
    }

    /**
     * Set idMotAmbigu
     *
     * @param integer $idMotAmbigu
     *
     * @return Mot_ambigu_phrase
     */
    public function setIdMotAmbigu($idMotAmbigu)
    {
        $this->idMotAmbigu = $idMotAmbigu;

        return $this;
    }

    /**
     * Get idMotAmbigu
     *
     * @return int
     */
    public function getIdMotAmbigu()
    {
        return $this->idMotAmbigu;
    }

    /**
     * Set idPhrase
     *
     * @param integer $idPhrase
     *
     * @return Mot_ambigu_phrase
     */
    public function setIdPhrase($idPhrase)
    {
        $this->idPhrase = $idPhrase;

        return $this;
    }

    /**
     * Get idPhrase
     *
     * @return int
     */
    public function getIdPhrase()
    {
        return $this->idPhrase;
    }
}
