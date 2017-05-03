<?php

namespace UserBundle\Repository;

/**
 * MembreRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MembreRepository extends \Doctrine\ORM\EntityRepository
{
	public function getOneByPseudoOrEmail($pseudoOrEmail){
		return $this->createQueryBuilder('m')
			->where("m.pseudo = :pseudo")->setParameter("pseudo", $pseudoOrEmail)
			->orWhere("m.email = :email")->setParameter("email", $pseudoOrEmail)
			->getQuery()->getOneOrNullResult();
	}

	public function getClassementGeneral($limit){
		return $this->createQueryBuilder('m')->select('m.id, m.pseudo, m.dateInscription, m.pointsClassement')
			->leftJoin("m.phrases", "p")->addSelect('count(distinct p.id) as nbPhrases')
			->leftJoin("p.likesPhrase", "lp", 'with', 'lp.active = 1')->addSelect('count(distinct lp.id) as nbLikes')
			->groupBy('m.id')
			->orderBy('m.pointsClassement', 'DESC')
			->setMaxResults($limit)
			->getQuery()->getResult();
	}

	public function getStat()
	{
		$array = array();

		$array['total'] = $this->createQueryBuilder('m')
			                  ->select('count(m) total')
			                  ->getQuery()->getSingleResult()['total'];

		$dateJ30 = new \DateTime();
		$dateJ30->setTimestamp($dateJ30->getTimestamp() - (3600 * 24 * 30));
		$array['inscriptionJ30'] = $this->createQueryBuilder('m')
			                           ->select('count(m) inscriptionJ30')
			                           ->where('m.dateInscription > :j30')->setParameter('j30', $dateJ30)
			                           ->getQuery()->getSingleResult()['inscriptionJ30'];

		$dateJ7 = new \DateTime();
		$dateJ7->setTimestamp($dateJ7->getTimestamp() - (3600 * 24 * 7));
		$array['inscriptionJ7'] = $this->createQueryBuilder('m')
			                          ->select('count(m) inscriptionJ7')
			                          ->where('m.dateInscription > :j7')->setParameter('j7', $dateJ7)
			                          ->getQuery()->getSingleResult()['inscriptionJ7'];

		$dateH24 = new \DateTime();
		$dateH24->setTimestamp($dateH24->getTimestamp() - (3600 * 24));
		$array['inscriptionH24'] = $this->createQueryBuilder('m')
			                           ->select('count(m) inscriptionH24')
			                           ->where('m.dateInscription > :h24')->setParameter('h24', $dateH24)
			                           ->getQuery()->getSingleResult()['inscriptionH24'];

		$array['bannis'] = $this->createQueryBuilder('m')
			                   ->select('count(m) bannis')
			                   ->where('m.banni = 1')
			                   ->getQuery()->getSingleResult()['bannis'];

		$array['inactifs'] = $this->createQueryBuilder('m')
			                     ->select('count(m) inactifs')
			                     ->where('m.actif = 0')
			                     ->getQuery()->getSingleResult()['inactifs'];

		$array['newsletter'] = $this->createQueryBuilder('m')
			                       ->select('count(m) newsletter')
			                       ->where('m.newsletter = 1')
			                       ->getQuery()->getSingleResult()['newsletter'];

		$array['homme'] = $this->createQueryBuilder('m')
			                  ->select('count(m) homme')
			                  ->where('m.sexe = :sexe')->setParameter('sexe', 'Homme')
			                  ->getQuery()->getSingleResult()['homme'];

		$array['femme'] = $this->createQueryBuilder('m')
			                  ->select('count(m) femme')
			                  ->where('m.sexe = :sexe')->setParameter('sexe', 'Femme')
			                  ->getQuery()->getSingleResult()['femme'];

		$array['moyPoints'] = $this->createQueryBuilder('m')
			                      ->select('avg(m.pointsClassement) moyPoints')
			                      ->getQuery()->getSingleResult()['moyPoints'];

		$array['moyCredits'] = $this->createQueryBuilder('m')
			                       ->select('avg(m.credits) moyCredits')
			                       ->getQuery()->getSingleResult()['moyCredits'];

		$sr = $this->createQueryBuilder('m1');
		$array['social'] = $this->createQueryBuilder('m')
			                   ->select('count(m) social')
			                   ->where($sr->expr()->isNotNull('m.idFacebook'))
			                   ->orWhere($sr->expr()->isNotNull('m.idTwitter'))
			                   ->getQuery()->getSingleResult()['social'];

		return $array;
	}

}
