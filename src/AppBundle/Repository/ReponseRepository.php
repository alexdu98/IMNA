<?php

namespace AppBundle\Repository;

/**
 * ReponseRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ReponseRepository extends \Doctrine\ORM\EntityRepository
{

	/**
	 * Retourne un tableau avec le nombre de votes pour la glose donnée
	 * @param $idPMA
	 * @param $glose
	 *
	 * @return mixed
	 */
	public function findByIdPMAetGloses($idPMA, $glose)
	{
		return $this->createQueryBuilder('r')
			->innerJoin("r.motAmbiguPhrase", "map", "WITH", "r.motAmbiguPhrase = map.id")
			->innerJoin("r.glose", "g", "WITH", "r.glose = g.id")->Select("count(g) as nbVotes")
			->where("map.id = :Ambi")->setParameter("Ambi", $idPMA)
			->andwhere("g.id = :Gl")->setParameter("Gl", $glose)
			->getQuery()->getSingleResult();
	}

	public function findGlosesforExport($map)
    {
        return $this->createQueryBuilder('r')
	        ->select('g.valeur, count(g) nbRep')
	        ->join('r.glose', 'g')
	        ->where('r.motAmbiguPhrase = :map')
	        ->setParameter('map', $map)
	        ->groupBy('g.valeur')
            ->getQuery()->getResult();
    }

	public function getStat()
	{
		$array = array();

		$array['total'] = $this->createQueryBuilder('r')
			                  ->select('count(r) total')
			                  ->getQuery()->getSingleResult()['total'];

		$dateJ30 = new \DateTime();
		$dateJ30->setTimestamp($dateJ30->getTimestamp() - (3600 * 24 * 30));
		$array['reponduJ30'] = $this->createQueryBuilder('r')
			                       ->select('count(r) reponduJ30')
			                       ->where('r.dateReponse > :j30')->setParameter('j30', $dateJ30)
			                       ->getQuery()->getSingleResult()['reponduJ30'];

		$dateJ7 = new \DateTime();
		$dateJ7->setTimestamp($dateJ7->getTimestamp() - (3600 * 24 * 7));
		$array['reponduJ7'] = $this->createQueryBuilder('r')
			                      ->select('count(r) reponduJ7')
			                      ->where('r.dateReponse > :j7')->setParameter('j7', $dateJ7)
			                      ->getQuery()->getSingleResult()['reponduJ7'];

		$dateH24 = new \DateTime();
		$dateH24->setTimestamp($dateH24->getTimestamp() - (3600 * 24));
		$array['reponduH24'] = $this->createQueryBuilder('r')
			                       ->select('count(r) reponduH24')
			                       ->where('r.dateReponse > :h24')->setParameter('h24', $dateH24)
			                       ->getQuery()->getSingleResult()['reponduH24'];

		return $array;
	}

}
