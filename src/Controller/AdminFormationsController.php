<?php
namespace App\Controller;

use App\Entity\Formation;
use App\Form\FormationType;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin', name: 'admin_')]
class AdminFormationsController extends AbstractController
{
    private $formationRepository;
    private $categorieRepository;

    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/formations', name: 'formations')]
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin_formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/formations/tri/{champ}/{ordre}/{table}', name: 'formations.sort')]
    public function sort(string $champ, string $ordre, string $table=""): Response
    {
        $formations = $this->formationRepository->findAllOrderBy($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin_formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    #[Route('/formations/recherche/{champ}/{table}', name: 'formations.findallcontain')]
    public function findAllContain(string $champ, Request $request, string $table=""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin_formations/index.html.twig', [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }

    #[Route('/formations/ajout', name: 'formation.ajout')]
    public function ajout(Request $request): Response
    {
        $formation = new Formation();
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin_formations');
        }

        return $this->render('admin_formations/ajout.html.twig', [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }

    #[Route('/formations/edit/{id}', name: 'formation.edit')]
    public function edit(Formation $formation, Request $request): Response
    {
        $formFormation = $this->createForm(FormationType::class, $formation);
        
        $formFormation->handleRequest($request);
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation);
            return $this->redirectToRoute('admin_formations');
        }

        return $this->render('admin_formations/edit.html.twig', [
            'formation' => $formation,
            'formformation' => $formFormation->createView()
        ]);
    }

    #[Route('/formations/suppr/{id}', name: 'formation.suppr')]
    public function suppr(Formation $formation): Response
    {
        $this->formationRepository->remove($formation);
        return $this->redirectToRoute('admin_formations');
    }
}