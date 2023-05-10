<?php

namespace App\Controller;

use App\Entity\Circuit;
use App\Form\CircuitType;
use Symfony\Component\PropertyAccess\PropertyAccess;
use App\Repository\CircuitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/circuit')]
class CircuitController extends AbstractController
{
    #[Route('/', name: 'app_circuit_index', methods: ['GET'])]
    public function index(CircuitRepository $circuitRepository): Response
    {
        return $this->render('circuit/index.html.twig', [
            'circuits' => $circuitRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_circuit_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CircuitRepository $circuitRepository): Response
    {
        $circuit = new Circuit();
        $form = $this->createForm(CircuitType::class, $circuit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $existingCircuit = $circuitRepository->findOneBy([
                'departc' => $circuit->getDepartc(),
                'arriveec' => $circuit->getArriveec(),
                'nomc' => $circuit->getNomc(),
            ]);
    
            if ($existingCircuit !== null) {
                $this->addFlash('danger', 'Circuit already exist !');
            } else {
                $circuitRepository->save($circuit, true);
                $this->addFlash('success', 'Circuit added successfully');
                return $this->redirectToRoute('app_circuit_index', [], Response::HTTP_SEE_OTHER);
            }
        }
    
        return $this->renderForm('circuit/new.html.twig', [
            'circuit' => $circuit,
            'form' => $form,
        ]);
    }
    #[Route('/{idcircuit}', name: 'app_circuit_show', methods: ['GET'])]
    #[ParamConverter('circuit', class:"App\Entity\Circuit")]
    public function show(Circuit $circuit): Response
    {
        dump($circuit);
            return $this->render('circuit/show.html.twig', [
                'circuit' => $circuit,
            ]);
        
    }

    #[Route('/{idcircuit}/edit', name: 'app_circuit_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Circuit $circuit, CircuitRepository $circuitRepository): Response
    {
        $form = $this->createForm(CircuitType::class, $circuit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
           // $originalCircuitData = $circuitRepository->getOriginalData($circuit);
           // $hydrator = new ObjectPropertyHydrator(PropertyAccess::createPropertyAccessor());
           // $circuit = new Circuit();
           // $hydrator->hydrate($originalCircuitData, $circuit);
           // $submittedCircuitData = $form->getData();
           // if ($originalCircuitData->getNomc() === $submittedCircuitData->getNomc()) {
          //      $this->addFlash('warning', 'You should modify the "nomc" field.');
           // }
            $circuitRepository->save($circuit, true);
            $this->addFlash('success', 'Circuit updated successfully.');
            return $this->redirectToRoute('app_circuit_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('circuit/edit.html.twig', [
            'circuit' => $circuit,
            'form' => $form,
        ]);
    }

    #[Route('/delete/{idcircuit}', name: 'app_circuit_delete', methods: ['POST'])]
    public function delete(Request $request, Circuit $circuit, CircuitRepository $circuitRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$circuit->getIdcircuit(), $request->request->get('_token'))) {
            $circuitRepository->remove($circuit, true);
        }
        $this->addFlash('success', 'Circuit deleted successfully !');
        return $this->redirectToRoute('app_circuit_index', [], Response::HTTP_SEE_OTHER);
    }


   
    #[Route('/search/aa', name: 'search_app_circuit', methods: ['GET'])]
    public function search(Request $request, CircuitRepository $circuitRepository): Response
    {
   
        $query = $request->query->get('query');
        $circuits = $circuitRepository->search($query);
      
      
          return $this->render('circuit/index.html.twig', [
              'circuits' => $circuits,
          ]);
    }
    
       

       
}
