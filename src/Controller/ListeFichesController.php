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
        //pagination
        $page = $request->query->getInt('page',1);
        $limit = 6;
        $fiches = $fichesRepository->paginationfiches($page, $limit); // appel la fonction dans le repository fiches avec les argument page et limit
        $maxPage = ceil($fiches->count()/ $limit); // a quoi sert ceil ??

        // Récupération de la liste des catégories, types et difficultés
        $categories = $categoriesRepository->findAll();
        $types = $typesRepository->findAll();
        $difficultes = $difficultesRepository->findAll();
        
        // Récupération des données du formulaire de recherche
        $Recherche = $request->request->get('recherche'); // Mot-clé pour la recherche
        $selectCategorie = $request->request->get('filtrecategorie');    // ID de la catégorie à filtrer
        $selectDifficulte = $request->request->get('filtredifficulte');    // ID de la catégorie à filtrer
        $selectType = $request->request->get('filtremuscles');    // ID de la catégorie à filtrer

        // Appel à la méthode du repository pour combiner recherche et filtre
        $fiches = $fichesRepository->findByTitleOrCategoryOrMusclesOrDifficulte($Recherche ,$selectCategorie ,$selectDifficulte ,$selectType);

        return $this->render('liste_fiches/index.html.twig', [
            'fiches' => $fiches,
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
