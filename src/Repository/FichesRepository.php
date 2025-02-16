<?php

namespace App\Repository;

use App\Entity\Fiches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Fiches>
 */
class FichesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fiches::class);
    }   

    public function findByTitleAndCategory(?string $recherche, ?int $Categorie): array
    {
        $data = $this->createQueryBuilder('fiches'); //l'entité Articles    

        // Si un mot-clé est présent, ajoute une condition LIKE pour le titre
        if ($recherche) {
            $data->andWhere('fiches.Nom LIKE :recherche')
               ->setParameter('recherche', '%' . $recherche . '%');
        }

        // Si un filtre de catégorie est présent, ajoute une condition pour l'ID de catégorie
        if ($Categorie) {
            $data->andWhere('fiches.Categorie = :Categorie')
               ->setParameter('Categorie', $Categorie);
        }

        return $data->getQuery()->getResult();
    }

    //    /**
    //     * @return Fiches[] Returns an array of Fiches objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('f.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Fiches
    //    {
    //        return $this->createQueryBuilder('f')
    //            ->andWhere('f.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
