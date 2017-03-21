<?php
/**
 * Created by PhpStorm.
 * User: MELY
 * Date: 3/13/2017
 * Time: 2:32 PM
 */

namespace AmbigussBundle\Controller;


use AmbigussBundle\Entity\Game;
use AmbigussBundle\Form\GameType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class GameController extends  Controller
{
    public function mainAction(Request $request)
    {

        $repository = $this->getDoctrine()->getManager()->getRepository('AmbigussBundle:Phrase');

        $randlist = array();
        $results = $repository->findall();
        // recup de tous les id dans un array
        foreach ($results as $result){
            array_push($randlist, $result->getId());
        }

        // prendre un id au hasard parmi la liste d'id et récupère son contenu
        shuffle($randlist);

	    $phraseOBJ = $repository->find($randlist[0]);
        $phraseEscape = preg_replace('#"#', '\"', $phraseOBJ->getContenu());

	    $game = new Game();
	    $form = $this->get('form.factory')->create(GameType::class, $game);
	    $form->add('valider', SubmitType::class, array(
	    	'label' => 'Valider',
		    'attr' => array('class' => 'btn btn-primary')
	    ));

	    $form->handleRequest($request);

	    if ($form->isSubmitted() && $form->isValid()){
		    $data = $form->getData();

		    $repository = $this->getDoctrine()->getManager()->getRepository('AmbigussBundle:MotAmbiguPhrase');
		    $repository2 = $this->getDoctrine()->getManager()->getRepository('AmbigussBundle:PoidsReponse');
		    $repository3 = $this->getDoctrine()->getManager()->getRepository('UserBundle:Niveau');

		    $em = $this->getDoctrine()->getManager();
		    foreach($data->reponses as $key => $rep){
			    $rep->setMotAmbiguPhrase($repository->find($request->request->get('ambigussbundle_game')
			                                               ['reponses'][$key]['idMotAmbiguPhrase']));
			    $rep->setContenuPhrase($rep->getMotAmbiguPhrase()->getPhrase()->getContenu());
			    $rep->setValeurMotAmbigu($rep->getMotAmbiguPhrase()->getMotAmbigu()->getValeur());
			    $rep->setValeurGlose($rep->getGlose()->getValeur());
			    if($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')){
				    $rep->setAuteur($this->getUser());
			    }
			    $rep->setPoidsReponse($repository2->findOneByPoidsReponse(1));
			    $rep->setNiveau($repository3->findOneByTitre('Facile'));

			    $em->persist($rep);
		    }
		    try{
			    $em->flush();
			    $this->get('session')->getFlashBag()->add('succes', "La réponse a bien été ajoutée");
		    }
		    catch(\Exception $e){
			    $this->get('session')->getFlashBag()->add('erreur', "Erreur lors de l'insertion de la réponse");
		    }

		    $hash = array();
		    $nb_point = 0;
		    $total = 0;
		    $repo4 = $this->getDoctrine()->getManager()->getRepository('AmbigussBundle:Reponse');
		    foreach($data->reponses as $rep){
		    	$ar = array();
			    foreach($rep->getMotAmbiguPhrase()->getMotAmbigu()->getGloses() as $g){
				    $compteur = $repo4->findByIdPMAetGloses($rep->getMotAmbiguPhrase(), $g->getId());
				    $ar[$g->getValeur()] = $compteur[1];
				    $total = $total + $ar[$g->getValeur()];
				    //$nb_point = $nb_point + (($compteur[1] / $total) * 100);
			    }
			    $hash[$rep->getValeurMotAmbigu()] = $ar;
		    }

		    return $this->render('AmbigussBundle:Game:after_play.html.twig', array (
		    	'phrase' => $phraseOBJ->getContenu(),
			    'stats' => $hash, // hashmap de type [motAmbigu => [glose -> nbvotes]]
			    'nb_point' => $nb_point
		    ));
	    }

	    $motsAmbigus = array();
	    for($i = 0; $i < $phraseOBJ->getMotsAmbigusPhrase()->count(); $i++){
			$motsAmbigus[] = array($phraseOBJ->getMotsAmbigusPhrase()->get($i)->getMotAmbigu()->getValeur(),
			                       $phraseOBJ->getMotsAmbigusPhrase()->get($i)->getId());
	    }

        return $this->render('AmbigussBundle:Game:play.html.twig', array(
            'form' => $form->createView(),
            'motsAmbigus' => json_encode($motsAmbigus),
            'phraseEscape' => $phraseEscape
        ));
    }

}