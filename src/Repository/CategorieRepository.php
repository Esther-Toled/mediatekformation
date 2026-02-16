<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Categorie>
 */
class CategorieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Categorie::class);
    }

    public function add(Categorie $categorie): void
    {
        $this->getEntityManager()->persist($categorie);
        $this->getEntityManager()->flush();
    }

    public function remove(Categorie $categorie): void
    {
        $this->getEntityManager()->remove($categorie);
        $this->getEntityManager()->flush();
    }
    
    /**
     * Retourne la liste des catégories des formations d'une playlist
     * @param type $idPlaylist
     * @return array
     */
    public function findAllForOnePlaylist($idPlaylist): array{
        return $this->createQueryBuilder('c')
                ->join('c.formations', 'f')
                ->join('f.playlist', 'p')
                ->where('p.id=:id')
                ->setParameter('id', $idPlaylist)
                ->orderBy('c.name', 'ASC')   
                ->getQuery()
                ->getResult();        
    }  
    
    public function findAllOrderByName(string $ordre = 'ASC') : array{
        return $this->createQueryBuilder('c')
                ->orderBy('c.name', $ordre)
                ->getQuery()
                ->getResult();
    }
}
