<?php
namespace App\Controller;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/categories', name: 'admin_categories')]
class AdminCategoriesController extends AbstractController
{
    private $categorieRepository;

    public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/', name: '')]
    public function index(Request $request): Response
    {
        $categories = $this->categorieRepository->findAll();

        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieType::class, $categorie);

        $formCategorie->handleRequest($request);
        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            
            $nomCategorie = $categorie->getName();
            $categorieExistante = $this->categorieRepository->findOneBy(['name' => $nomCategorie]);

            if ($categorieExistante) {
                $this->addFlash('danger', 'Impossible d\'ajouter : la catégorie "'.$nomCategorie.'" existe déjà.');
            } else {
                $this->categorieRepository->add($categorie);
                return $this->redirectToRoute('admin_categories');
            }
        }

        return $this->render('admin_categories/index.html.twig', [
            'categories' => $categories,
            'formcategorie' => $formCategorie->createView()
        ]);
    }

    #[Route('/suppr/{id}', name: '.suppr')]
    public function suppr(Categorie $categorie): Response
    {
        if ($categorie->getFormations()->count() > 0) {
            $this->addFlash('danger', 'Impossible de supprimer cette catégorie car elle est associée à des formations.');
            return $this->redirectToRoute('admin_categories');
        }

        $this->categorieRepository->remove($categorie);
        return $this->redirectToRoute('admin_categories');
    }
}