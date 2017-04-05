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
			->leftJoin("m.phrases", "p")->addSelect('count(p.auteur) as nbPhrases')
			->leftJoin("p.likesPhrase", "lp", 'with', 'lp.active = 1')->addSelect('count(lp) as nbLikes')
			->groupBy('m.id')
			->orderBy('m.pointsClassement', 'DESC')
			->setMaxResults($limit)
			->getQuery()->getResult();
	}
}
