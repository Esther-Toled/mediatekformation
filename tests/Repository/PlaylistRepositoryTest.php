<?php

namespace App\tests\Repository;

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of VisiteRepositoryTest
 *
 * @author toled
 */
class PlaylistRepositoryTest extends KernelTestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function recupRepository(): PlaylistRepository
    {
        
        return $this->entityManager->getRepository(Playlist::class);
    }

    
    public function testNbPlaylist(): void
    {
        $repository = $this->recupRepository();
        $nbPlaylist = $repository->count([]);
        $this->assertEquals(28, $nbPlaylist);
    }
   
    public function newPlaylist(): Playlist{
        $playlist = (new Playlist())
                ->setName("Un Nom");
        return $playlist;
    }
    
    public function testAddPlaylist(): void
    {
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $nbPlaylist = $repository->count([]);
        $this->entityManager->persist($playlist);
        $this->entityManager->flush();
        $this->assertEquals($nbPlaylist + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testSupprPlaylist(){
        $repository = $this->recupRepository();
        $playlist = $this->newPlaylist();
        $repository->add($playlist, true);
        $nbPlaylist = $repository->count([]);
        $repository->remove($playlist, true);
        $this->assertEquals($nbPlaylist - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
     
}