<?php

namespace AmbigussBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Phrase
 *
 * @ORM\Table(name="phrase")
 * @ORM\Entity(repositoryClass="AmbigussBundle\Repository\PhraseRepository")
 */
class Phrase
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
     * @ORM\Column(name="contenu", type="string", length=512)
     */
    private $contenu;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modification", type="datetime", nullable=true)
     */
    private $dateModification;

    /**
     * @var bool
     *
     * @ORM\Column(name="signale", type="boolean")
     */
    private $signale;

    /**
     * @var bool
     *
     * @ORM\Column(name="visible", type="boolean")
     */
    private $visible;

    /**
     * @ORM\ManyToOne(targetEntity="Membre")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auteur;

	/**
	 * @ORM\ManyToOne(targetEntity="Membre")
	 */
	private $modificateur;


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
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Phrase
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set dateCreation
     *
     * @param \DateTime $dateCreation
     *
     * @return Phrase
     */
    public function setDateCreation($dateCreation)
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * Get dateCreation
     *
     * @return \DateTime
     */
    public function getDateCreation()
    {
        return $this->dateCreation;
    }

    /**
     * Set dateModification
     *
     * @param \DateTime $dateModification
     *
     * @return Phrase
     */
    public function setDateModification($dateModification)
    {
        $this->dateModification = $dateModification;

        return $this;
    }

    /**
     * Get dateModification
     *
     * @return \DateTime
     */
    public function getDateModification()
    {
        return $this->dateModification;
    }

    /**
     * Set signale
     *
     * @param boolean $signale
     *
     * @return Phrase
     */
    public function setSignale($signale)
    {
        $this->signale = $signale;

        return $this;
    }

    /**
     * Get signale
     *
     * @return bool
     */
    public function getSignale()
    {
        return $this->signale;
    }

    /**
     * Set visible
     *
     * @param boolean $visible
     *
     * @return Phrase
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set auteur
     *
     * @param \AmbigussBundle\Entity\Membre $auteur
     *
     * @return Phrase
     */
    public function setAuteur(\AmbigussBundle\Entity\Membre $auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return \AmbigussBundle\Entity\Membre
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set modificateur
     *
     * @param \AmbigussBundle\Entity\Membre $modificateur
     *
     * @return Phrase
     */
    public function setModificateur(\AmbigussBundle\Entity\Membre $modificateur = null)
    {
        $this->modificateur = $modificateur;

        return $this;
    }

    /**
     * Get modificateur
     *
     * @return \AmbigussBundle\Entity\Membre
     */
    public function getModificateur()
    {
        return $this->modificateur;
    }
}