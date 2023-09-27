<?php

namespace App\Controller;

use App\Entity\Evenement;
use App\Entity\Inscription;
use App\Form\EventMakerType;
use App\Form\InscriptionEvenementType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\EvenementRepository;
use App\Repository\InscriptionRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Controller\EvenementController;

class HomeController extends AbstractController
{

    private $evenementController;

    public function __construct(EvenementController $evenementController)
    {
        $this->evenementController = $evenementController;
    }

    #[Route('/home', name: 'app_home')]
    public function index(EvenementRepository $evenementRepository, Request $request, EntityManagerInterface $manager): Response
    {
        
        return $this->render('home/index.html.twig', [
            'evenements' => array_reverse($evenementRepository->findAll()),
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
                'Votre évenement a bien été créé.'
            );

            return $this->redirectToRoute(('app_home'));

        }

        return $this->render('home/eventMaker.html.twig', [
            'form' =>  $form->createView()
        ]);
    }

    #[Route('/MyRegistration', name:'app_myRegistration')]
    public function myRegistration(Request $request, InscriptionRepository $inscriptionRepository, EvenementRepository $evenementRepository): Response 
    {
        $user = $this->getUser();

        $inscriptions = $inscriptionRepository->findBy(['user' => $user]);
        $evenements = [];

        foreach ( $inscriptions as $inscription) {
            $evenements = $evenementRepository->findBy(['id'=> $inscription->getEvenementId()]);   
        }
        
        return $this->render('home/myRegistration.html.twig', [
            'inscriptions' => array_reverse($inscriptions),
            'evenements' => array_reverse($evenements),
        ]);
    }

}
