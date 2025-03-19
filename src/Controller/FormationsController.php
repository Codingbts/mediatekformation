<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur des formations côté utilisateur.
 *
 * Ce contrôleur permet de lister, trier, rechercher et afficher les formations.
 */
class FormationsController extends AbstractController
{
    /**
     * Repository des formations.
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Repository des catégories.
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Chemin vers la page des formations.
     * @var string
     */
    const FORMATIONPAGE = "pages/formations.html.twig";

    /**
     * Chemin vers la page d'une formation.
     * @var string
     */
    const FORMATIONPAGESHOW = "pages/formation.html.twig";

    /**
     * Constructeur.
     * @param FormationRepository $formationRepository
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * Affiche la liste des formations.
     * @return Response
     */
    #[Route('/formations', name: 'formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::FORMATIONPAGE, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Trie les formations par champ et ordre.
     * @param string $champ Champ de tri
     * @param string $ordre Ordre de tri (ASC/DESC)
     * @param string $table Table associée (optionnel)
     * @return Response
     */
    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort($champ, $ordre, $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::FORMATIONPAGE, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    /**
     * Recherche des formations contenant une valeur dans un champ.
     * @param string $champ Champ de recherche
     * @param Request $request Requête HTTP
     * @param string $table Table associée (optionnel)
     * @return Response
     */
    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::FORMATIONPAGE, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Affiche une formation spécifique.
     * @param int $id ID de la formation
     * @return Response
     */
    #[Route('/formations/formation/{id}', name: 'formations.showone')]
    public function showOne($id): Response
    {
        $formation = $this->formationRepository->find($id);
        return $this->render(self::FORMATIONPAGESHOW, [
            'formation' => $formation,
        ]);
    }
}
