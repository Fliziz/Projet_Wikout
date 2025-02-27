<?php

namespace App\Controller;

use App\Repository\FichesRepository;
use App\Repository\TypeRepository;
use App\Repository\CategoriesRepository;
use App\Repository\DifficulteRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ListeFichesController extends AbstractController
{
    #[Route('/liste/fiches', name: 'liste_fiches')]
    public function index(FichesRepository $fichesRepository, CategoriesRepository $categoriesRepository, TypeRepository $typesRepository, DifficulteRepository $difficultesRepository , Request $request): Response
    {   
        // Pagination
        $page = $request->query->getInt('page',1);
        $limit = 6;

        // Récupération des données du formulaire de recherche
        $Recherche = $request->request->get('recherche'); 
        $selectCategorie = $request->request->get('filtreCategorie');    
        $selectDifficulte = $request->request->get('filtreDifficulte');    
        $selectType = $request->request->get('filtreType');    


        if ($Recherche || $selectCategorie || $selectDifficulte || $selectType ) {
            // Si des filtres sont appliqués, on recherche avec les critères
            $fiches = $fichesRepository->findByTitleOrCategoryOrMusclesOrDifficulte($Recherche, $selectCategorie, $selectDifficulte, $selectType);
            $maxPage = 1; // Pas de pagination sur la recherche
        } else {
            // Sinon, on applique la pagination
            $fiches = $fichesRepository->paginationFiches($page, $limit);
            $maxPage = ceil($fiches->count() / $limit);
        }

        // Récupération de la liste des catégories, types et difficultés
        $categories = $categoriesRepository->findAll();
        $types = $typesRepository->findAll();
        $difficultes = $difficultesRepository->findAll();

        return $this->render('liste_fiches/index.html.twig', [
            'fiches' => $fiches,
            'maxPage' => $maxPage,
            'page' => $page,
            //
            'selectCategorie' => $selectCategorie,
            'selectDifficulte' => $selectDifficulte,
            'selectType' => $selectType,

            'Recherche' => $Recherche,
            //
            'categories' => $categories,
            'types'=> $types,
            'difficultes'=> $difficultes,
        ]);
    }
}
