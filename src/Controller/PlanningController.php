<?php

namespace App\Controller;

use App\Entity\Circuit;
use App\Entity\Moyenstransport;
use App\Entity\Planning;
use App\Form\Planning1Type;
use App\Form\PlanningType;
use App\Repository\CircuitRepository;
use App\Repository\MoyenstransportRepository;
use App\Repository\PlanningRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/planning')]
class PlanningController extends AbstractController
{
    #[Route('/', name: 'app_planning_index', methods: ['GET'])]
    public function index(PlanningRepository $planningRepository): Response
    {
        //pour get time now
        $current_time = new \DateTime();
        $current = $current_time->format('H:i:s');

        //pour transferer string to datetime for dateArriver
        foreach ($planningRepository->findall() as $f) {
            $dateaDateTime = DateTime::createFromFormat('H:i:s', $f->getDatea());
            $dateArriver = $dateaDateTime->format('H:i:s');
            if ($current > $dateArriver) {
                // dd($f->getDated(),$f->getIdcir()->getIdcircuit(),$f->getIdmoy()->getIdmoy(),$f->getIdmoy()->getCapacite());
                $planningRepository->UpdateNbplaceToCapacite($f->getDated(), $f->getIdcir()->getIdcircuit(), $f->getIdmoy()->getIdmoy(), $f->getIdmoy()->getCapacite());
            }
        }

        //selectionner distinct les types de transport && circuit
        $transports = $planningRepository->getDistinctTransports();
        $circuits=$planningRepository->getDistinctCircuits();
        // dd($TRANSPORT, $CIRCUIT);
        return $this->render('planning/index.html.twig', [
            'plannings' => $planningRepository->findAll(),
            'transports' => $transports,
            'circuits' => $circuits,
        ]);
    }

    #[Route('/new', name: 'app_planning_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PlanningRepository $planningRepository): Response
    {
        $planning = new Planning();
        $form = $this->createForm(Planning1Type::class, $planning);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $planning = $form->getData();
            $nbplace = (int) $planning->getIdmoy()->getCapacite();
            $planning->setNbplace($nbplace);

            //tester si date de depart > date d'arriver (je travaille avec ce methode par ce que dated et date type string donc assert impossible)
            $datedDateTime = DateTime::createFromFormat('H:i:s', $form->get('dated')->getData());
            $dateaDateTime = DateTime::createFromFormat('H:i:s', $form->get('datea')->getData());
            if ($datedDateTime > $dateaDateTime) {
                $message = [
                    'msg' => "L'heure de depart doit etre inferieur a l'heure darriveé !",
                    'form' => $form->createView(),
                ];
                return $this->render('planning/new.html.twig', $message);
            }

            $planningRepository->save($planning, true);

            $this->addFlash('notice', ' créer avec succès !');
            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }






        return $this->renderForm('planning/new.html.twig', [
            'planning' => $planning,
            'form' => $form,
        ]);
    }

    #[Route('/show/{dated}/{idcir}/{idmoy}', name: 'app_planning_show', methods: ['GET'])]
    public function show(Planning $planning): Response
    {
        return $this->render('planning/show.html.twig', [
            'planning' => $planning,
        ]);
    }

    #[Route('/{dated}/{idcir}/{idmoy}/edit', name: 'app_planning_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        $form = $this->createForm(Planning1Type::class, $planning);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //tester si date de depart > date d'arriver (je travaille avec ce methode par ce que dated et date type string donc assert impossible)
            $datedDateTime = DateTime::createFromFormat('H:i:s', $form->get('dated')->getData());
            $dateaDateTime = DateTime::createFromFormat('H:i:s', $datea = $form->get('datea')->getData());
            if ($datedDateTime > $dateaDateTime) {
                $message = [
                    'msg' => "L'heure de depart doit etre inferieur a l'heure darriveé !",
                    'form' => $form->createView(),
                ];
                return $this->render('planning/edit.html.twig', $message);
            }
            $planningRepository->save($planning, true);

            $this->addFlash('notice', ' Modifié avec succès !');
            return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('planning/edit.html.twig', [
            'planning' => $planning,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{dated}/{idcir}/{idmoy}', name: 'app_planning_delete', methods: ['POST'])]
    public function delete(Request $request, Planning $planning, PlanningRepository $planningRepository): Response
    {
        if ($this->isCsrfTokenValid('delete_' . $planning->getDated() . '_' . $planning->getIdcir()->getIdcircuit() . '_' . $planning->getIdmoy()->getIdmoy(), $request->request->get('_token'))) {
            $planningRepository->remove($planning, true);
        }
        // $this->addFlash('notice',' Supprimer avec succès !');
        return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/rech', name: 'app_recherche_pla')]
    // public function Recherche(Request $request , PlanningRepository $planningRepository): Response
    // {
    //     $filter = $request->query->get('filter');
    //     $selectedOption = $request->query->get('selectedOption');
    //     if($selectedOption=="1"){
    //         // $planning=$planningRepository->FilterPlanningByMoyen($filter);
    //         $planning=$planningRepository->filterPlanningByEnter($filter);
    //     }
    //     else if($selectedOption=="2"){
    //         $planning=$planningRepository->FilterPlanningByCircuit($filter);
    //         // dd($planning);
    //     }
    //     else{
    //         $planning=$planningRepository->FilterPlanningByDateD($filter);
    //     }
    //     return $this->render('planning/index.html.twig', [
    //         'plannings' => $planning,
    //     ]);
    // }


    #[Route('/rechercheP', name: 'app_recherche_pla')]
    public function recherchea(Request $request, PlanningRepository $planningRepository): Response
    {
        $filter = $request->query->get('filter');
        $planning = $planningRepository->filterPlanningByEnter($filter);
        // dd($planning);
        $TRANSPORT = $planningRepository->getDistinctTransports();
        $CIRCUIT=$planningRepository->getDistinctCircuits();
        // dd($TRANSPORT, $CIRCUIT);
        return $this->render('planning/index.html.twig', [
            'plannings' => $planning,
            'transports' => $TRANSPORT,
            'circuits' => $CIRCUIT,
        ]);
    }

    #[Route('/rechercheAvance', name: 'app_recherche_Avance', methods: ['GET', 'POST'])]
    public function rechercheAV(Request $request, PlanningRepository $planningRepository): Response
    {
        $circuit = $request->query->get('circuit');
        $transport = $request->query->get('transport');
        $intervalle1 = $request->query->get('intervalle1');
        $intervalle2 = $request->query->get('intervalle2');

        // dd($circuit ,$transport, $intervalle1 ,  $intervalle2);


        $plannings=$planningRepository->getPlanningAvanceR($circuit,$transport,$intervalle1,$intervalle2);

        $TRANSPORT = $planningRepository->getDistinctTransports();
        $CIRCUIT=$planningRepository->getDistinctCircuits();

        return $this->render('planning/index.html.twig', [
            'plannings' => $plannings,
            'transports' => $TRANSPORT,
            'circuits' => $CIRCUIT,
        ]);
    }



    #[Route('/{dated}/{idcir}/{idmoy}/{nbPlace}', name: 'app_useradd_pla', methods: ['GET', 'POST'])]
    public function addReservation(Request $request, PlanningRepository $planningRepository, string $dated, int $idcir, int $idmoy , int $nbPlace): Response
    {
        if($nbPlace==0){
            $this->addFlash('added','nombre de place est plain !');
            return $this->redirectToRoute('app_planning_index');
        }
        
        $planningRepository->AddOneToReservation($dated, $idcir, $idmoy);
        return $this->redirectToRoute('app_planning_index', [], Response::HTTP_SEE_OTHER);
    }
}
