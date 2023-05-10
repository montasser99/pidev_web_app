<?php

/* src/AppBundle/Controller/DefaultController */

namespace App\Controller;

use App\Repository\ReclamationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Entity;
use AppBundle\Repository\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
  /**
   * Creates a new ActionItem entity.
   *
   * @Route("/search", name="ajax_search")
   * @Method("GET")
   */
  public function searchAction(Request $request, ReclamationRepository $reclamationRepository)
  {
      

      $requestString = $request->get('q');
      
      $reclamation =  $reclamationRepository->findEntitiesByString($requestString);
      
      if(!$reclamation) {
        $result['reclamation']['error'] = "Result not found";
    } else 
    {
        $result['reclamation'] = $this->getRealEntities($reclamation);
    }
    

    return new Response(json_encode($result));
}

  public function getRealEntities($reclamations){

      foreach ($reclamations as $reclamation){
     $realEntities[$reclamation->getIdr()] = [
       
                $reclamation->getNom(),
                $reclamation->getPrenom(),
                $reclamation->getDater(),
                $reclamation->getDescrec()
            ];
        }
        

// Renvoyer le tableau formaté
return $realEntities;
}
}