<?php 
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\User;
use Faker\Factory;
use Doctrine\ORM\EntityManagerInterface;

class EvenementControllerTest extends WebTestCase
{



    public function testCreateEvenement()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/createEvent'); // Remplacez par la route réelle de création d'événements

        // creation d'un utilisateur pour créer l'évènement
        $faker = Factory::create('fr_FR');
        $user = new User();
        $user->setUsername($faker->userName());
        $user->setEmail($faker->email());
        $user->setPassword(password_hash('password', PASSWORD_BCRYPT)); 

        // Remplacez ces lignes par les étapes réelles pour soumettre le formulaire de création d'événements
        $form = $crawler->selectButton('submit')->form();
        $form['evenement[nom]'] = $faker->sentence($nbWords = 6, $variableNbWords = true);
        $form['evenement[description]'] = $faker->paragraph();
        $form['evenement[dateDebut]'] = $faker->dateTimeThisMonth();
        $form['evenement[dateFin]'] = $faker->dateTimeThisMonth();
        $form['evenement[User]'] = $user;
        $form['evenement[lieu]'] = $faker->city();
        // Remplissez d'autres champs du formulaire si nécessaire

        $client->submit($form);

        // Assurez-vous que la création de l'événement a réussi
        $this->assertResponseRedirects('/evenements'); // Remplacez par la route de redirection après la création
    }
}
?>