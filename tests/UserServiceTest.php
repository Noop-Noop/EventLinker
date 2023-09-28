<?php 
use PHPUnit\Framework\TestCase;
use App\Service\UserService;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Faker\Factory;

class UserServiceTest extends WebTestCase
{
    public function testCreateNewUser()
    {
        $faker = Factory::create('fr_FR');

        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Submit')->form();
        $form['registration[username]'] = ($faker->userName());
        $form['registration[email]'] = ($faker->email());
        $form['registration[password][first]'] = 'PassWord1!';
        $form['registration[password][second]'] = 'PassWord1!';

        $client->submit($form);
        $this->assertResponseRedirects('/home');
    }

    public function testCreateUserKnownUsername()
    {
        $faker = Factory::create('fr_FR');

        $client = static::createClient();
        $crawler = $client->request('GET', '/register');

        $form = $crawler->selectButton('Submit')->form();
        $form['registration[username]'] = ('nouvelutilisateur');
        $form['registration[email]'] = ($faker->email());
        $form['registration[password][first]'] = 'PassWord1!';
        $form['registration[password][second]'] = 'PassWord1!';

        $client->submit($form);
        $this->assertResponseRedirects('/register');
    }

    public function testLoginUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();
        $form['username'] = 'nouvelutilisateur';
        $form['password'] = 'PassWord1!';

        $client->submit($form);
        $this->assertResponseRedirects('/home');
    }

    public function testLoginUnknownUser()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();
        $form['username'] = 'UnknownUser';
        $form['password'] = 'Unknown/Password9'; 

        $client->submit($form);
        $this->assertResponseRedirects('/login');
    }

    public function testLogout()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Sign in')->form();
        $form['username'] = 'nouvelutilisateur';
        $form['password'] = 'PassWord1!';

        $client->submit($form);;
        $client->request('GET', '/logout');

        $this->assertResponseRedirects('/home');
    }
}
?>