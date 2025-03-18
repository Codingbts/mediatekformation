<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategorieController
 *
 * @author Saad
 */
class AdminCategorieController extends AbstractController {

    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;

    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;

    const ADMINFORMATIONPAGE = "admin/admin.categories.html.twig";

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository) {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/admin/categorie', name: 'admin.categories')]
    public function index(): Response {
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::ADMINFORMATIONPAGE, [
                    'categories' => $categories
        ]);
    }

    #[Route('/admin/categorie/suppr/{id}', name: 'admin.categories.suppr')]
    public function suppr(int $id): Response {
        $categorie = $this->categorieRepository->find($id);

        if (!$categorie) {
            $this->addFlash('error', 'Aucune catégories trouvées !');
            return $this->redirectToRoute('admin.categories');
        }
        
        if ($categorie->getFormations()->count() > 0) {
            $this->addFlash('error', 'Impossible de supprimer cette catégorie car elle est liée à des formations !');
            return $this->redirectToRoute('admin.categories');
        }

        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin.categories');
    }

    #[Route('/admin/categorie/ajout', name: 'admin.categories.ajout')]
    public function ajout(Request $request): Response {
        $nomCategories = $request->get("name");
        
        if (empty($nomCategories)) {
            $this->addFlash('error', 'Le champ pour créer une nouvelle catégorie doit être remplit !');
            return $this->redirectToRoute('admin.categories');
        }
        
        $categorieExist = $this->categorieRepository->findOneByName($nomCategories);

        if ($categorieExist) {
            $this->addFlash('error', 'La catégorie que vous essayez de créer existe déjà !');
            return $this->redirectToRoute('admin.categories');
        }

        $categorie = new Categorie();

        $categorie->setName($nomCategories);
        $this->categorieRepository->add($categorie);
        return $this->redirectToRoute('admin.categories');
    }
}
