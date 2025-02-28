<?php

namespace App\Repository;

use App\Entity\Type;
use App\Entity\Fiches;
use App\Entity\Categories;
use App\Entity\Difficulte;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<Fiches>
 */
class FichesRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fiches::class);
    }   // Ce constructeur configure le repository pour qu'il soit prêt à interagir avec l'entité Fiches via le gestionnaire d'entités fourni par $registry.

    public function findByTitleOrCategoryOrMusclesOrDifficulte(?string $Recherche, ?int $Categorie_Id, ?int $Difficulte_Id, ?int $Type_Id): array
    {
        $typeRepository = $this->getEntityManager()->getRepository(Type::class);
        $categorieRepository = $this->getEntityManager()->getRepository(Categories::class);
        $difficulteRepository = $this->getEntityManager()->getRepository(Difficulte::class);

        $data = $this->createQueryBuilder('fiches');

        if ($Recherche) {
            $data->andWhere('fiches.Nom LIKE :Recherche')
               ->setParameter('Recherche', '%' . $Recherche . '%');
        }

        if ($Type_Id ) {
            $Type = $typeRepository->find($Type_Id);
            if ($Type) {
                $data->andWhere('fiches.Type = :Type')
                   ->setParameter('Type', $Type);
            }
        }

        if ($Difficulte_Id) {
            $Difficulte = $difficulteRepository->find($Difficulte_Id);
            if ($Difficulte) {
                $data->andWhere('fiches.Difficulte = :Difficulte')
                   ->setParameter('Difficulte', $Difficulte);
            }
        }

        if ($Categorie_Id) {
            $Categorie = $categorieRepository->find($Categorie_Id);
            if ($Categorie) {
                $data->andWhere('fiches.Categorie = :Categorie')
                   ->setParameter('Categorie', $Categorie);
            }
        }

        return $data->getQuery()->getResult();
    }
    
    public function paginationFiches(int $page , int $limit): Paginator
    {
        return new Paginator($this
            ->createQueryBuilder('pagination')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->setHint( Paginator :: HINT_ENABLE_DISTINCT, false),
             false # Permet d'enlever le distinct dans la requette sql pour gagner en performance 
        );
    }

    public function findByIdDesc(): array
       {
           return $this->createQueryBuilder('fiches')
               ->orderBy('fiches.id', 'DESC')
               ->setMaxResults(6)
               ->getQuery()
               ->getResult()
           ;
       }
}
