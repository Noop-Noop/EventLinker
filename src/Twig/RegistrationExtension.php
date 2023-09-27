<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\InscriptionRepository;



class RegistrationExtension extends AbstractExtension
{
        // Injectez le gestionnaire d'entités ou d'autres services dont vous avez besoin ici

        private $entityManager;
        private $inscriptionRepository;
    
        public function __construct(InscriptionRepository $inscriptionRepository, EntityManagerInterface $entityManager)
        {
            $this->inscriptionRepository = $inscriptionRepository;
            $this->entityManager = $entityManager;
        }

        public function getFunctions(): array
        {
            return [
                new TwigFunction('isUserRegistered', [$this, 'isUserRegistered'])
            ];
        }


        public function isUserRegistered($userId, $eventId)
        {
            $inscription = $this->inscriptionRepository->findOneBy(['user' => $userId, 'evenement' => $eventId]);
    
            return $inscription !== null;
        }


}
?>