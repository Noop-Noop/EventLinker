<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Form\EventMakerType;
use App\Form\EventEditType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use App\Repository\InscriptionRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\EvenementController;
use App\Form\DateFilterType;
use DateTime;
use Doctrine\ORM\Mapping\OrderBy;

class HomeController extends AbstractController
{

    private $evenementController;

    public function __construct(EvenementController $evenementController)
    {
        $this->evenementController = $evenementController;
    }

    #[Route('/', name: 'app_home')]
    public function index(EvenementRepository $evenementRepository, Request $request, EntityManagerInterface $manager): Response
    {

        $today = new DateTime();

        $upcomingEvents = $evenementRepository->createQueryBuilder('e')
            ->where('e.date_debut >= :today')
            ->setParameter('today', $today)
            ->OrderBy('e.date_debut', 'ASC')
            ->getQuery()->getResult();
            
        $dateFilterForm = $this->createForm(DateFilterType::class);
        $dateFilterForm-> handleRequest($request);

        $startDate = null;
        $endDate = null;

        if ($dateFilterForm->isSubmitted() && $dateFilterForm->isValid()) {
            $data = $dateFilterForm->getData();
            $startDate = $data['startDate'];
            $endDate = $data['endDate'];
        }

        $filteredEvents = $evenementRepository->findEventsInDateRange($startDate, $endDate);
        // trie par ordre chronologique
        usort($filteredEvents, function ($a, $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });

        return $this->render('home/index.html.twig', [
            'filteredEvents' => $filteredEvents,
            'dateFilterForm' => $dateFilterForm->createView(),
            'evenements' => $upcomingEvents // $evenementRepository->findBy([], ['date_debut' => 'ASC']),
        ]);
    }

    #[Route('/createEvent', name: 'app_createEvent')]
    public function createEvent(Request $request, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();
        $event = new Evenement();

        $form = $this->createForm(EventMakerType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();    
                // Vérifiez si un fichier a été téléchargé
            if ($imageFile) {
                // Générez un nom de fichier unique
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Déplacez le fichier vers un répertoire le stockage 
                $imageFile->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );
                // Mettez à jour la propriété de l'entité avec le nom du fichier
                $event->setImage($newFilename);
            }

            $event = $form->getData();
            $event->setIdCreateur($user);
            $manager->persist($event);
            $manager->flush();

            $this->addFlash(
                'success', 
                'Votre évènement a bien été créé.'
            );

            return $this->redirectToRoute(('app_createEvent'));
        }

        return $this->render('home/eventMaker.html.twig', [
            'form' =>  $form->createView()
        ]);
    }

    #[Route('/showEvent/{evenementId}', name: 'app_showEvent')]
    public function showEvent(Request $request, EvenementRepository $evenementRepository, $evenementId): Response
    {
        $routeName = $request->query->get('routeName');
        $previousRouteName = $routeName;


        return $this->render('home/showEvent.html.twig', [
            'previousRouteName' => $previousRouteName,
            'routeName' => $routeName,            
            'evenement' => $evenementRepository->find($evenementId),
        ]);
    }

    #[Route('/editEvent/{evenementId}', name: 'app_editEvent')]
    public function editEvent(EvenementRepository $evenementRepository, Request $request, EntityManagerInterface $manager, $evenementId): Response
    {
        $routeName = $request->query->get('routeName');
        $previousRouteName = $routeName;

        $user = $this->getUser();
        $event = $evenementRepository->find($evenementId);
        $form = $this->createForm(EventEditType::class, $event);
        // dd($form);
        $event->setIdCreateur($user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $imageFile */
            $imageFile = $form->get('image')->getData();    
                    // Vérifiez si un fichier a été téléchargé
            if ($imageFile) {
                // Générez un nom de fichier unique
                $newFilename = uniqid().'.'.$imageFile->guessExtension();

                // Déplacez le fichier vers un répertoire le stockage 
                $imageFile->move(
                    $this->getParameter('image_directory'),
                    $newFilename
                );
                // Mettez à jour la propriété de l'entité avec le nom du fichier
                $event->setImage($newFilename);
            }

            $event = $form->getData();
            $manager->persist($event);
            $manager->flush();

            $this->addFlash(
                'success', 
                'Votre évènement a bien été modifié.'
            );



            return $this->redirectToRoute($routeName);
        }



        return $this->render('home/editEvent.html.twig', [
            'previousRouteName' => $previousRouteName,
            'routeName' => $routeName,       
            'evenement' => $evenementRepository->find($evenementId),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/deleteEvent/{evenementId}', name: 'app_deleteEvent')]
    public function deleteEvent(EvenementRepository $evenementRepository, Request $request, EntityManagerInterface $manager, $evenementId): Response
    {
        $user = $this->getUser();     
        $evenement = $evenementRepository->findOneBy([
            'id' => $evenementId,            
            'id_createur' => $user
        ]);
        $manager->remove($evenement);
        $manager->flush();

        $this->addFlash('success', 'Cet événement a bien été supprimer.');
        $routeName = $request->query->get('routeName');
        return $this->redirectToRoute($routeName);
    }

    #[Route('/MyEvent', name:'app_myEvent')]
    public function myEvent(Request $request, EvenementRepository $evenementRepository): Response 
    {
        $user = $this->getUser();
        $evenements = $evenementRepository->findBy(['id_createur' => $user]);
            
        return $this->render('home/myRegistration.html.twig', [
            'evenements' => array_reverse($evenements),
        ]);
    }

    #[Route('/MyRegistration', name:'app_myRegistration')]
    public function myRegistration(Request $request, InscriptionRepository $inscriptionRepository, EvenementRepository $evenementRepository): Response 
    {
        $user = $this->getUser();
        $inscriptions = $inscriptionRepository->findBy(['user' => $user]);
        $evenements = [];

        foreach ( $inscriptions as $inscription) {
            $evenements[] = $inscription->getEvenementId();
        }     


        usort($evenements, function ($a, $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });

        return $this->render('home/myRegistration.html.twig', [
            'inscriptions' => $inscriptions,
            'evenements' => $evenements,
        ]);
    }

}
