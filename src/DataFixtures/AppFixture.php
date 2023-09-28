<?php
namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Evenement;
use App\Entity\User;
use Faker\Factory;
use App\Repository\UserRepository;

class AppFixture extends Fixture
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
        
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');


        $user = $this->userRepository->findOneBy(['id' => 1]);



        for ($i = 0; $i < 10; $i++) { // Créez 10 utilisateurs et 1 événements créés par chacun de ces utilisateurs
            $event = new Evenement();
            $event->setNom($faker->sentence($nbWords = 6, $variableNbWords = true));
            $event->setDescription($faker->paragraph());
            $event->setDateDebut($faker->dateTimeThisMonth());
            $event->setDateFin($faker->dateTimeThisMonth());
            $user = new User();
            $user->setUsername($faker->userName());
            $user->setEmail($faker->email());
            $user->setPassword(password_hash('password', PASSWORD_BCRYPT)); 
            $manager->persist($user);          
            $event->setIdCreateur($user);
            $event->setLieux($faker->city());
            $manager->persist($event);
        }

        $manager->flush();
    }
}
?>