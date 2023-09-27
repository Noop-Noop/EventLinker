<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Inscription;
use App\Repository\EvenementRepository;
use App\Repository\InscriptionRepository;


class EvenementController extends AbstractController
{
    private $entityManager;
    private $inscriptionRepository;
    private $evenementRepository;

    public function __construct(InscriptionRepository $inscriptionRepository, EvenementRepository $evenementRepository, EntityManagerInterface $entityManager)
    {
        $this->evenementRepository = $evenementRepository;
        $this->inscriptionRepository = $inscriptionRepository;
        $this->entityManager = $entityManager;
    }

    public function isUserRegistered($userId, $eventId)
    {
        $inscription = $this->inscriptionRepository->findOneBy(['userId' => $userId, 'evenementId' => $eventId]);

        return $inscription !== null;
    }

    #[Route('/event/register/{evenementId}', name: 'app_evenement_register')]
    public function register(Request $request, $evenementId): Response
    {
        $user = $this->getUser();
        $evenement = $this->evenementRepository->findOneBy(['id' => $evenementId]);
        $inscription = new Inscription();   
        $inscription->setUserId($user);
        $inscription->setEvenementId($evenement);
        $this->entityManager->persist($inscription);
        $this->entityManager->flush();

        $this->addFlash('success', 'Vous êtes maintenant inscrit à cet l\'événement.');
        $routeName = $request->query->get('routeName');
        return $this->redirectToRoute($routeName);
    }

    #[Route('/event/unregister/{evenementId}', name: 'app_evenement_unregister')]
    public function unregister(Request $request, $evenementId): Response
    {
        $user = $this->getUser();
        
        $inscription = $this->inscriptionRepository->findOneBy([
            'user' => $user,
            'evenement' => $evenementId,
        ]);

        $this->entityManager->remove($inscription);
        $this->entityManager->flush();

        $this->addFlash('success', 'Vous êtes maintenant désinscrit de cet l\'événement.');
        $routeName = $request->query->get('routeName');
        return $this->redirectToRoute($routeName);
    }





    #[Route('/evenement', name: 'app_evenement')]
    public function index(): Response
    {
        return $this->render('evenement/index.html.twig', [
            'controller_name' => 'EvenementController',
        ]);
    }
}
