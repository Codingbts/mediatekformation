<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur des playlists côté utilisateur.
 *
 * Ce contrôleur permet de lister, trier, rechercher et afficher les playlists.
 */
class PlaylistsController extends AbstractController
{
    /**
     * Repository des playlists.
     * @var PlaylistRepository
     */
    private $playlistRepository;

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
     * Chemin vers la page des playlists.
     * @var string
     */
    const PAGEPLAYLISTS = "pages/playlists.html.twig";

    /**
     * Chemin vers la page d'une playlist.
     * @var string
     */
    const ONEPAGEPLAYLIST = "pages/playlist.html.twig";

    /**
     * Constructeur.
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRespository
     */
    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRespository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }

    /**
     * Affiche la liste des playlists.
     * @return Response
     */
    #[Route('/playlists', name: 'playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Trie les playlists par champ et ordre.
     * @param string $champ Champ de tri
     * @param string $ordre Ordre de tri (ASC/DESC)
     * @return Response
     */
    #[Route('/playlists/tri/{champ}/{ordre}', name: 'playlists.sort')]
    public function sort($champ, $ordre): Response
    {
        if ($champ === "name") {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif ($champ === "formations") {
            $playlists = $this->playlistRepository->findAllOrderByFormationNb($ordre);
        }

        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
     * Recherche des playlists contenant une valeur dans un champ.
     * @param string $champ Champ de recherche
     * @param Request $request Requête HTTP
     * @param string $table Table associée (optionnel)
     * @return Response
     */
    #[Route('/playlists/recherche/{champ}/{table}', name: 'playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGEPLAYLISTS, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Affiche une playlist spécifique.
     * @param int $id ID de la playlist
     * @return Response
     */
    #[Route('/playlists/playlist/{id}', name: 'playlists.showone')]
    public function showOne($id): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $playlistCategories = $this->categorieRepository->findAllForOnePlaylist($id);
        $playlistFormations = $this->formationRepository->findAllForOnePlaylist($id);
        return $this->render(self::ONEPAGEPLAYLIST, [
            'playlist' => $playlist,
            'playlistcategories' => $playlistCategories,
            'playlistformations' => $playlistFormations,
        ]);
    }
}
