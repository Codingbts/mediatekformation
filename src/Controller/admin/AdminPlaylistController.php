<?php

namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur pour la gestion des playlists côté admin.
 *
 * Ce contrôleur permet de gérer les playlists (affichage, tri, recherche, suppression, édition, ajout).
 */
class AdminPlaylistController extends AbstractController
{
    /**
     * Repository des playlists.
     * @var PlaylistRepository
     */
    private $playlistRepository;

    /**
     * Repository des catégories.
     * @var CategorieRepository
     */
    private $categorieRepository;

    /**
     * Repository des formations.
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     * Chemin vers la page d'administration des playlists.
     * @var string
     */
    const ADMINPLAYLISTPAGE = "admin/admin.playlists.html.twig";

    /**
     * Chemin vers la page d'édition des playlists.
     * @var string
     */
    const ADMINPLAYLISTPAGEEDIT = "admin/admin.playlist.edit.html.twig";

    /**
     * Constructeur.
     * @param PlaylistRepository $playlistRepository
     * @param CategorieRepository $categorieRepository
     * @param FormationRepository $formationRepository
     */
    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRepository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRepository;
    }

    /**
     * Affiche la liste des playlists.
     * @return Response
     */
    #[Route('/admin/playlists', name: 'admin.playlists')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAll();
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMINPLAYLISTPAGE, [
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
    #[Route('/admin/playlists/tri/{champ}/{ordre}', name: 'admin.playlists.sort')]
    public function sort($champ, $ordre): Response
    {
        if ($champ === "name") {
            $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        } elseif ($champ === "formations") {
            $playlists = $this->playlistRepository->findAllOrderByFormationNb($ordre);
        }

        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMINPLAYLISTPAGE, [
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
    #[Route('/admin/playlists/recherche/{champ}/{table}', name: 'admin.playlists.findallcontain')]
    public function findAllContain($champ, Request $request, $table = ""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMINPLAYLISTPAGE, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    /**
     * Supprime une playlist par son ID.
     * @param int $id ID de la playlist
     * @return Response
     */
    #[Route('/admin/playlists/suppr/{id}', name: 'admin.playlists.suppr')]
    public function suppr(int $id): Response
    {
        $playlist = $this->playlistRepository->find($id);

        if (!$playlist) {
            $this->addFlash('error', 'Aucune playlist trouvée');
            return $this->redirectToRoute("admin.playlists");
        }

        if ($playlist->getFormationNb() > 0) {
            $this->addFlash('error', 'Vous ne pouvez pas supprimer la playlist car elle contient des formations !');
            return $this->redirectToRoute('admin.playlists');
        }

        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin.playlists');
    }

    /**
     * Édite une playlist existante.
     * @param int $id ID de la playlist
     * @param Request $request Requête HTTP
     * @return Response
     */
    #[Route('/admin/playlists/edit/{id}', name: 'admin.playlist.edit')]
    public function edit(int $id, Request $request): Response
    {
        $playlist = $this->playlistRepository->find($id);
        $categories = $this->categorieRepository->findAll();
        $formations = $playlist->getFormations();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);

        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render(self::ADMINPLAYLISTPAGEEDIT, [
            'playlist' => $playlist,
            'formPlaylist' => $formPlaylist,
            'categories' => $categories,
            'formations' => $formations,
            'is_edit' => true
        ]);
    }

    /**
     * Ajoute une nouvelle playlist.
     * @param Request $request Requête HTTP
     * @return Response
     */
    #[Route('/admin/playlists/ajout', name: 'admin.playlist.ajout')]
    public function ajout(Request $request): Response
    {
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        $formations = $playlist->getFormations();

        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin.playlists');
        }
        return $this->render(self::ADMINPLAYLISTPAGEEDIT, [
            'playlist' => $playlist,
            'formPlaylist' => $formPlaylist->createView(),
            'formations' => $formations,
            'is_edit' => false
        ]);
    }
}
