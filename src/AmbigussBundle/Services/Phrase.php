<?php
/**
 * Created by PhpStorm.
 * User: Alexandre
 * Date: 08/04/2017
 * Time: 23:35
 */

namespace AmbigussBundle\Services;

class Phrase extends \Twig_Extension
{

	public function getStaticHTML($phrase)
	{
		$phraseO = new \AmbigussBundle\Entity\Phrase();
		$phraseO->setContenu($phrase);

		return $phraseO->getContenuHTML();
	}

	public function getFunctions()
	{
		return array(
			new \Twig_SimpleFunction('getPhraseHTML', array(
				$this,
				'getStaticHTML',
			)),
		);
	}

	public function getName()
	{
		return 'getPhraseHTML';
	}
}