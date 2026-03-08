<?php

namespace App\tests\Repository;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of VisiteRepositoryTest
 *
 * @author toled
 */
class CategorieRepositoryTest extends KernelTestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function recupRepository(): CategorieRepository
    {
        
        return $this->entityManager->getRepository(Categorie::class);
    }

    
    public function testNbCategorie(): void
    {
        $repository = $this->recupRepository();
        $nbCategorie = $repository->count([]);
        $this->assertEquals(9, $nbCategorie);
    }
   
    public function newCategorie(): Categorie{
        $categorie = (new Categorie())
                ->setName("Un Nom");
        return $categorie;
    }
    
    public function testAddCategorie(): void
    {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $nbCategorie = $repository->count([]);
        $this->entityManager->persist($categorie);
        $this->entityManager->flush();
        $this->assertEquals($nbCategorie + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testSupprCategorie(): void {
        $repository = $this->recupRepository();
        $categorie = $this->newCategorie();
        $repository->add($categorie, true);
        $nbCategorie = $repository->count([]);
        $repository->remove($categorie, true);
        $this->assertEquals($nbCategorie - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
     
}