<?php


namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of PlaylistControllerTest
 *
 * @author toled
 */
class PlaylistControllerTest extends WebTestCase {
    
    public function testAccesPage():void 
    {
        $client = static::createClient();
        $client->catchExceptions(false);
        
        $client->request('GET', '/playlists');
        $response=$client->getResponse();
        
        $this->assertSame(200, $response->getStatusCode());
    }
    
    public function testLinkPlaylist()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/playlists');

        // Cliquer sur le lien "Voir détail" de la première playlist
        $link = $crawler->filter('tbody tr:first-child td:last-child a')->attr('href');

        // Suivre le lien
        $crawler = $client->request('GET', $link);

        // Vérifie que la page existe
        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());

        // Vérifie que la route correspond bien
        $uri = $client->getRequest()->server->get("REQUEST_URI");
        $expectedUri = preg_replace('#.*/playlists/playlist/(\d+)#', '/playlists/playlist/$1', $link);
        $this->assertEquals($expectedUri, $uri);
    }
    
    public function testFilterFormation(): void
    {
        $client = static::createClient();
        $client->request('GET', '/playlists');
        $crawler = $client->submitForm('filtrer',[
            'recherche' => 'Cours'
        ]);
        $this->assertCount(22, $crawler->filter('h5'));
        $this->assertSelectorTextContains('h5', 'Cours Composant logiciel');
    }
    
    public function testSortFormation()
    {
        $client = static::createClient();
        $client->request('GET', '/playlists/tri/name/ASC');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
        $client->request('GET', '/playlists/tri/nombre/ASC');
        $this->assertSelectorTextContains('h5', 'playlist test');
        $client->request('GET', '/playlists/tri/niombre/DESC');
        $this->assertSelectorTextContains('h5', 'Bases de la programmation (C#)');
    }
}
