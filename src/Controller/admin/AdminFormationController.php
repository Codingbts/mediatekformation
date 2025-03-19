<?php

namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la gestion des formations côté admin.
 *
 * Ce contrôleur permet de gérer les formations (affichage, tri, recherche, suppression, édition, ajout).
 */
class AdminFormationController extends AbstractController
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
     * Chemin vers la page d'administration des formations.
     * @var string
     */
    const ADMINFORMATIONPAGE = "admin/admin.formations.html.twig";

    /**
     * Chemin vers la page d'édition des formations.
     * @var string
     */
    const ADMINFORMATIONPAGEEDIT = "admin/admin.formation.edit.html.twig";

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
    #[Route('/admin', name: 'admin.formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMINFORMATIONPAGE, [
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
    #[Route('/admin/tri/{champ}/{ordre}/{table}', name: 'admin.formations.sort')]
    public function sort($champ, $ordre, $table = ""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMINFORMATIONPAGE, [
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
    #[Route('/admin/recherche/{champ}/{table}', name: 'admin.formations.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMINFORMATIONPAGE, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Supprime une formation par son ID.
     * @param int $id ID de la formation
     * @return Response
     */
    #[Route('/admin/suppr/{id}', name: 'admin.formations.suppr')]
    public function suppr(int $id): Response
    {
        $formation = $this->formationRepository->find($id);
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute('admin.formations');
    }

    /**
     * Édite une formation existante.
     * @param int $id ID de la formation
     * @param Request $request Requête HTTP
     * @return Response
     */
    #[Route('/admin/edit/{id}', name: 'admin.formation.edit')]
    public function edit(int $id, Request $request): Response
    {
        $formation = $this->formationRepository->find($id);
        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render(self::ADMINFORMATIONPAGEEDIT, [
            'formation' => $formation,
            'formFormation' => $formFormation
        ]);
    }

    /**
     * Ajoute une nouvelle formation.
     * @param Request $request Requête HTTP
     * @return Response
     */
    #[Route('/admin/ajout', name: 'admin.formation.ajout')]
    public function ajout(Request $request): Response
    {
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);

        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin.formations');
        }
        return $this->render(self::ADMINFORMATIONPAGEEDIT, [
            'formation' => $formation,
            'formFormation' => $formFormation->createView()
        ]);
    }
}
