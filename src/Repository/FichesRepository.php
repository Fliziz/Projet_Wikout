<?php

namespace App\Repository;

use App\Entity\Fiches;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @extends ServiceEntityRepository<Fiches>
 */
class FichesRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fiches::class);
    }   // Ce constructeur configure le repository pour qu'il soit prêt à interagir avec l'entité Fiches via le gestionnaire d'entités fourni par $registry.

    public function findByTitleOrCategoryOrMusclesOrDifficulte(?string $Recherche, ?int $Categorie , ?int $Difficulte , ?int $Muscles): array
    {
        $data = $this->createQueryBuilder('fiches'); //l'entité Articles    

        // Si un mot-clé est présent, ajoute une condition LIKE pour le titre
        if ($Recherche) {
            $data->andWhere('fiches.Nom LIKE :Recherche')
               ->setParameter('Recherche', '%' . $Recherche . '%');
        }

        if ($Muscles) {
            $data->andWhere('fiches.Muscles LIKE :Mucles')
               ->setParameter('Mucles', '%' . $Mucles . '%');
        }

        if ($Difficulte) {
            $data->andWhere('fiches.Difficulte LIKE :Difficulte')
               ->setParameter('Difficulte', '%' . $Difficulte . '%');
        }

        // Si un filtre de catégorie est présent, ajoute une condition pour l'ID de catégorie
        if ($Categorie) {
            $data->andWhere('fiches.Categorie = :Categorie')
               ->setParameter('Categorie', $Categorie);
        }

        return $data->getQuery()->getResult();
    }
    
    public function paginationFiches(int $page , int $limit): Paginator
    {
        return new Paginator($this
            ->createQueryBuilder('r')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->setHint( Paginator :: HINT_ENABLE_DISTINCT, false),
             false # Permet d'enlever le distinct dans la requette sql pour gagner en performance 
        );
    }
}
