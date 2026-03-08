<?php

namespace App\tests\Repository;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Description of VisiteRepositoryTest
 *
 * @author toled
 */
class FormationRepositoryTest extends KernelTestCase
{
    private EntityManager $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function recupRepository(): FormationRepository
    {
        
        return $this->entityManager->getRepository(Formation::class);
    }

    
    public function testNbFormations()
    {
        $repository = $this->recupRepository();
        $nbFormation = $repository->count([]);
        $this->assertEquals(238, $nbFormation);
    }
   
    public function newFormation(): Formation{
        $formation = (new Formation())
                ->setTitle("Un titre")
                ->setDescription("Description")
                ->setPublishedAt(new \DateTime("yesterday"));
        return $formation;
    }
    
    public function testAddFormation()
    {
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $nbFormation = $repository->count([]);
        $this->entityManager->persist($formation);
        $this->entityManager->flush();
        $this->assertEquals($nbFormation + 1, $repository->count([]), "erreur lors de l'ajout");
    }
    
    public function testSupprFormation(){
        $repository = $this->recupRepository();
        $formation = $this->newFormation();
        $repository->add($formation, true);
        $nbFormation = $repository->count([]);
        $repository->remove($formation, true);
        $this->assertEquals($nbFormation - 1, $repository->count([]), "erreur lors de la suppression");
    }
    
     
}