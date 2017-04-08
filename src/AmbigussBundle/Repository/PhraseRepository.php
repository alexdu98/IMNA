<?php

namespace AmbigussBundle\Repository;

/**
 * PhraseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PhraseRepository extends \Doctrine\ORM\EntityRepository
{
    public function getClassementPhrases($limit){
	    return $this->createQueryBuilder('p')->select("p.contenu, p.dateCreation ")->distinct()
		    ->addSelect('(SELECT COUNT(lp2.id) FROM AmbigussBundle\Entity\AimerPhrase lp2 WHERE lp2.phrase = p.id AND lp2.active = 1) as nbLikes')
		    ->leftJoin("p.likesPhrase", "lp", 'WITH', 'lp.id = p.id')
		    ->leftJoin("p.parties", "pa", 'WITH', 'pa.phrase = p.id')->addSelect('sum(pa.gainCreateur) as nbPoints')
            ->groupBy('p.id')
            ->orderBy('nbLikes', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()->getResult();
    }

	public function getClassementPhrasesUser($user)
	{
		return $this->createQueryBuilder('p')->select('p.contenu, p.dateCreation')->distinct()
			->addSelect('(SELECT COUNT(lp2.id) FROM AmbigussBundle\Entity\AimerPhrase lp2 WHERE lp2.phrase = p.id AND lp2.active = 1) as nbLikes')
			->leftJoin("p.likesPhrase", "lp", 'WITH', 'lp.id = p.id')
			->leftJoin("p.parties", "pa", 'WITH', 'pa.phrase = p.id')->addSelect('sum(pa.gainCreateur) as nbPoints')
			->where('p.auteur = :user')->setParameter('user', $user)
			->groupBy('p.id')
			->orderBy('nbPoints', 'DESC')
			->getQuery()->getResult();
	}
}
