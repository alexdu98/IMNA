<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Membre;

class PhraseRepository extends \Doctrine\ORM\EntityRepository
{

    /**
     * Retourne le classement des phrases de tous les joueurs
     *
     * @param int $limit
     * @return array
     */
    public function getClassementPhrases(int $limit){
	    return $this->createQueryBuilder('p')->select("p.id, p.contenu, p.dateCreation, p.gainCreateur")->distinct()
		    ->addSelect('(SELECT COUNT(lp2.id) FROM AppBundle\Entity\JAime lp2 WHERE lp2.phrase = p.id AND lp2.active = 1) as nbJAime')
		    ->leftJoin("p.jAime", "lp", 'WITH', 'lp.id = p.id')
            ->groupBy('p.id')
            ->orderBy('nbJAime', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()->getResult();
    }

    /**
     * Retourne le classement des phrases du membre
     *
     * @param Membre $user
     * @return array
     */
	public function getClassementPhrasesUser(Membre $user)
	{
		return $this->createQueryBuilder('p')
            ->select('p.id, p.contenu, p.dateCreation, p.gainCreateur')
            ->distinct()
			->addSelect('(SELECT COUNT(lp2.id) FROM AppBundle\Entity\JAime lp2 WHERE lp2.phrase = p.id AND lp2.membre != :user AND lp2.active = 1) as nbJAime')
			->leftJoin("p.jAime", "lp", 'WITH', 'lp.id = p.id')
			->where('p.auteur = :user')->setParameter('user', $user)
			->groupBy('p.id')
			->orderBy('p.gainCreateur', 'DESC')
			->getQuery()->getResult();
	}

	/**
	 * Retoune un tableau de tableau avec un champ correspondant à l'id d'une phrase non joué et existante depuis plus de $dureeAv par le membre
	 *
	 * @param Membre $membre
	 * @param int $dureeAvantJouabiliteSecondes
	 *
	 * @return array
	 */
	public function findIdPhrasesNotPlayedByMembre(Membre $membre, int $dureeAvantJouabiliteSecondes)
	{
		$date = new \DateTime();
		$dateMin = $date->setTimestamp($date->getTimestamp() - $dureeAvantJouabiliteSecondes);

		$sub = $this->_em->getRepository('AppBundle:Partie')->createQueryBuilder('pa')
			->select('identity(pa.phrase)')
			->where('pa.joueur = :membre')
			->andWhere('pa.joue = 1');

		$q = $this->createQueryBuilder('ph');

		return $q->select('ph.id')
			->where($q->expr()->notIn('ph.id', $sub->getDQL()))
			->andWhere('ph.dateCreation < :dateMin')
			->andWhere('ph.auteur != :membre')
			->andWhere('ph.visible = 1')
			->setParameter('dateMin', $dateMin->format('Y-m-d H:i:s'))
			->setParameter('membre', $membre)
			->getQuery()->getArrayResult();
	}

    /**
     * Retourne les id de phrases visibles pouvant être jouées
     *
     * @param bool $isConnected
     * @param int $dureeAvantJouabiliteSecondes
     * @return array
     */
    public function findRandom(bool $isConnected, int $dureeAvantJouabiliteSecondes, int $nbPhrasesDisponiblesNonConnecte)
    {
        $date = new \DateTime();
        $dateMin = $date->setTimestamp($date->getTimestamp() - $dureeAvantJouabiliteSecondes);

        $query = $this->createQueryBuilder('p')
            ->select('p.id')
            ->where('p.dateCreation < :dateMin')->setParameter('dateMin', $dateMin)
            ->andWhere('p.visible = 1');

        if (!$isConnected) {
            $query->setMaxResults($nbPhrasesDisponiblesNonConnecte)->orderBy('p.id', 'ASC');
        }

        return $query->getQuery()->getArrayResult();
    }

    /**
     * Retourne le nombre de phrases visibles
     *
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
	public function countAllVisible()
	{
		return $this->createQueryBuilder('p')
			->select('count(p) nbPhrases')
			->where('p.visible = 1')
			->getQuery()->getSingleResult();
	}

    /**
     * Retourne le nombre de phrases signalées
     *
     * @return mixed
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
	public function countSignale()
	{
		return $this->createQueryBuilder('p')
			       ->select('count(p) signale')
			       ->where('p.signale = 1')
			       ->getQuery()->getSingleResult()['signale'];
	}

    /**
     * Retourne les informations pour l'export de phrases
     *
     * @return array
     */
	public function export()
    {
        $query = $this->createQueryBuilder('p')
            ->select('p.id idp, p.contenu phrase, map.ordre, ma.valeur vma, g.valeur vg, count(p) nbRep')
            ->innerJoin('p.motsAmbigusPhrase', 'map')
            ->innerJoin('map.motAmbigu', 'ma')
            ->innerJoin('map.reponses', 'r')
            ->innerJoin('r.glose', 'g')
            ->where('p.visible = 1')
            ->groupBy('p.contenu, map.ordre, ma.valeur, g.valeur')
            ->orderBy('p.id, map.ordre, ma.id', 'ASC')
            ->getQuery();

        return $query->getArrayResult();
    }

    public function getByDayForMembre(Membre $membre)
    {
        $query = $this->createQueryBuilder('p')
            ->select('DATE_FORMAT(p.dateCreation, \'%Y-%m-%d\') as date, count(p) as count')
            ->where('p.auteur = :membre')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->setParameter('membre', $membre);

        return $query->getQuery()->getResult();
    }

    /**
     * Retourne un tableau de statistiques de l'entité
     *
     * @return array
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
	public function getStat()
	{
		$array = array();

		$array['total'] = $this->createQueryBuilder('p')
			                  ->select('count(p) total')
			                  ->getQuery()->getSingleResult()['total'];

		$dateJ30 = new \DateTime();
		$dateJ30->setTimestamp($dateJ30->getTimestamp() - (3600 * 24 * 30));
		$array['creationJ30'] = $this->createQueryBuilder('p')
			                        ->select('count(p) creationJ30')
			                        ->where('p.dateCreation > :j30')->setParameter('j30', $dateJ30)
			                        ->getQuery()->getSingleResult()['creationJ30'];

		$dateJ7 = new \DateTime();
		$dateJ7->setTimestamp($dateJ7->getTimestamp() - (3600 * 24 * 7));
		$array['creationJ7'] = $this->createQueryBuilder('p')
			                       ->select('count(p) creationJ7')
			                       ->where('p.dateCreation > :j7')->setParameter('j7', $dateJ7)
			                       ->getQuery()->getSingleResult()['creationJ7'];

		$dateH24 = new \DateTime();
		$dateH24->setTimestamp($dateH24->getTimestamp() - (3600 * 24));
		$array['creationH24'] = $this->createQueryBuilder('p')
			                        ->select('count(p) creationH24')
			                        ->where('p.dateCreation > :h24')->setParameter('h24', $dateH24)
			                        ->getQuery()->getSingleResult()['creationH24'];

		$array['signale'] = $this->createQueryBuilder('p')
			                    ->select('count(p) signale')
			                    ->where('p.signale = 1')
			                    ->getQuery()->getSingleResult()['signale'];

		$array['moyGain'] = $this->createQueryBuilder('p')
			                    ->select('avg(p.gainCreateur) moyGain')
			                    ->getQuery()->getSingleResult()['moyGain'];

		return $array;
	}

}
