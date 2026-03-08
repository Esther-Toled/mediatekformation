<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;


/**
 * Description of FormationControllerTest
 *
 * @author toled
 */
class FormationControllerTest extends WebTestCase{
    
    public function testAccesPage():void 
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $client->request('GET', '/formations');
        $response=$client->getResponse();
        
        $this->assertSame(200, $response->getStatusCode());
    }
    
    public function testLinkFormations()
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->getCrawler();
        $link = $crawler->selectLink('image miniature')->link();
        $client->click($link);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertSelectorExists('h4');
        
    }
    
    public function testFilterFormation(): void
    {
        $client = static::createClient();
        $client->request('GET', '/formations');
        $crawler = $client->submitForm('filtrer',[
            'recherche' => 'Eclipse n°3 : GitHub et Eclipse'
        ]);
        $this->assertCount(1, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Eclipse n°3 : GitHub et Eclipse');
    }
    
    public function testSortFormation()
    {
        $client = static::createClient();
        $client->request('GET', '/formations/tri/title/ASC');
        $this->assertSelectorTextContains('h5', 'Android Studio (complément n°1) : Navigation Drawer et Fragment');
        $client->request('GET', '/formations/tri/name/DESC/playlist');
        $this->assertSelectorTextContains('h5', 'C# : ListBox en couleur');
        $client->request('GET', '/formations/tri/publishedAt/ASC');
        $this->assertSelectorTextContains('h5', 'Cours UML (1 à 7 / 33) : introduction et cas d\'utilisation');
    }
    
}
