<?php
namespace App\Tests;

use App\Services\NameGenerator;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\DomCrawler\Form;

class EmailNotificationListenerTest extends WebTestCase
{

    private function fillForm(Crawler $crawler, string $submitButton, array $fields): Form
    {
        $form = $crawler->selectButton($submitButton)->form();
        foreach ($fields as $key => $value) {
            $form[$key] = $value;
        }
        return $form;
    }

    public function testUserIsAddedSuccessfully(): void
    {
        $client = self::createClient();
        $client->followRedirects();

        $crawler = $client->request('GET', '/admin/user/create');
        $loginForm = $this->fillForm($crawler, 'Sign in', [
            'email' => 'admin@ito.dev',
            'password' => 'awesome'
        ]);
        $crawler = $client->submit($loginForm);

        $newUserEmail = sprintf('testuser_%s@ito.dev', rand(1000, 1000000));
        $addUserForm = $this->fillForm($crawler, 'Create user', [
            'user[name]' => (new NameGenerator())->getNewName(),
            'user[email]' => $newUserEmail,
            'user[plainPassword][first]' => 123456,
            'user[plainPassword][second]' => 123456,
        ]);
        $crawler = $client->submit($addUserForm);
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(1, $crawler->filter("td:contains('{$newUserEmail}')")->count());
        $client->click($crawler->filter('a[title="Delete"]')->last()->link());
    }
}
