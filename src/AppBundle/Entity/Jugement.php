<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jugement
 *
 * @ORM\Table(name="jugement", indexes={
 *     @ORM\Index(name="ix_jugem_verdid", columns={"verdict_id"}),
 *     @ORM\Index(name="ix_jugem_typobjid", columns={"type_objet_id"}),
 *     @ORM\Index(name="ix_jugem_jugeid", columns={"juge_id"}),
 *     @ORM\Index(name="ix_jugem_catjugemid", columns={"categorie_jugement_id"}),
 *     @ORM\Index(name="ix_jugem_autid", columns={"auteur_id"}),
 *     @ORM\Index(name="ix_jugem_dtcreat", columns={"date_creation"}),
 *     @ORM\Index(name="ix_jugem_dtdelib", columns={"date_deliberation"}),
 *     @ORM\Index(name="ix_jugem_objid", columns={"objet_id"})
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Repository\JugementRepository")
 */
class Jugement implements \JsonSerializable
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
     * @ORM\Column(name="description", type="string", length=512)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime")
     */
    private $dateCreation;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_deliberation", type="datetime", nullable=true)
     */
    private $dateDeliberation;

    /**
     * @var int
     *
     * @ORM\Column(name="objet_id", type="integer")
     */
    private $objetId;

    /**
     * @ORM\ManyToOne(targetEntity="CategorieJugement")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorieJugement;

	/**
	 * @ORM\ManyToOne(targetEntity="TypeObjet")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $typeObjet;

	/**
	 * @ORM\ManyToOne(targetEntity="TypeVote")
	 */
	private $verdict;

	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Membre")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $auteur;

	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Membre")
	 */
	private $juge;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->dateCreation = new \DateTime();
    }

    /**
     * Get dateDeliberation
     *
     * @return \DateTime
     */
	public function getDateDeliberation()
    {
	    return $this->dateDeliberation;
    }

    /**
     * Set dateDeliberation
     *
     * @param \DateTime $dateDeliberation
     *
     * @return Jugement
     */
    public function setDateDeliberation($dateDeliberation)
    {
        $this->dateDeliberation = $dateDeliberation;

        return $this;
    }

    /**
     * Get objetId
     *
     * @return int
     */
	public function getObjetId()
    {
	    return $this->objetId;
    }

    /**
     * Set objetId
     *
     * @param integer $objetId
     *
     * @return Jugement
     */
    public function setObjetId($objetId)
    {
        $this->objetId = $objetId;

        return $this;
    }

    /**
     * Get typeObjet
     *
     * @return TypeObjet
     */
	public function getTypeObjet()
    {
	    return $this->typeObjet;
    }

    /**
     * Set typeObjet
     *
     * @param TypeObjet $typeObjet
     *
     * @return Jugement
     */
    public function setTypeObjet(TypeObjet $typeObjet)
    {
        $this->typeObjet = $typeObjet;

        return $this;
    }

    /**
     * Get verdict
     *
     * @return TypeVote
     */
	public function getVerdict()
    {
	    return $this->verdict;
    }

    /**
     * Set verdict
     *
     * @param TypeVote $verdict
     *
     * @return Jugement
     */
    public function setVerdict(TypeVote $verdict = null)
    {
        $this->verdict = $verdict;

        return $this;
    }

    /**
     * Get juge
     *
     * @return Membre
     */
	public function getJuge()
    {
	    return $this->juge;
    }

    /**
     * Set juge
     *
     * @param Membre $juge
     *
     * @return Jugement
     */
    public function setJuge(Membre $juge = null)
    {
        $this->juge = $juge;

        return $this;
    }

	/**
	 * AUTRES
	 */

    public function jsonSerialize()
	{
		return array(
			'id' => $this->getId(),
			'categorieJugement' => $this->getCategorieJugement()->getNom(),
			'description' => $this->getDescription(),
			'dateCreation' => $this->getDateCreation()->getTimestamp(),
			'auteur' => $this->getAuteur()->getUsername(),
			'auteur_id' => $this->getAuteur()->getId(),
		);
	}

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
	 * Get categorieJugement
	 *
	 * @return CategorieJugement
	 */
	public function getCategorieJugement()
	{
		return $this->categorieJugement;
	}

	/**
	 * Set categorieJugement
	 *
	 * @param CategorieJugement $categorieJugement
	 *
	 * @return Jugement
	 */
	public function setCategorieJugement(CategorieJugement $categorieJugement)
	{
		$this->categorieJugement = $categorieJugement;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 *
	 * @return Jugement
	 */
	public function setDescription($description)
	{
		$this->description = $description;

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
	 * Set dateCreation
	 *
	 * @param \DateTime $dateCreation
	 *
	 * @return Jugement
	 */
	public function setDateCreation($dateCreation)
	{
		$this->dateCreation = $dateCreation;

		return $this;
	}

	/**
	 * Get auteur
     *
     * @return Membre
     */
	public function getAuteur()
    {
	    return $this->auteur;
    }

	/**
	 * Set auteur
	 *
	 * @param Membre $auteur
	 *
	 * @return Jugement
	 */
	public function setAuteur(Membre $auteur)
	{
		$this->auteur = $auteur;

		return $this;
    }

}
