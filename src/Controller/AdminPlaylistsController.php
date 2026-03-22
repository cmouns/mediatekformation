<?php
namespace App\Controller;

use App\Entity\Playlist;
use App\Form\PlaylistType;
use App\Repository\CategorieRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/playlists', name: 'admin_playlists')]
class AdminPlaylistsController extends AbstractController
{
    private $playlistRepository;
    private $categorieRepository;

    public function __construct(PlaylistRepository $playlistRepository, CategorieRepository $categorieRepository)
    {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
    }

    #[Route('/', name: '')]
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();

        return $this->render('admin_playlists/index.html.twig', [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    #[Route('/tri/{champ}/{ordre}', name: '.sort')]
    public function sort(string $champ, string $ordre): Response
    {
        switch($champ){
            case "name":
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
                break;
            case "nbformations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
            default:
                $playlists = $this->playlistRepository->findAllOrderByName('ASC');
                break;
        }
        $categories = $this->categorieRepository->findAll();
        
        return $this->render("admin_playlists/index.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories            
        ]);
    }          

    #[Route('/recherche/{champ}/{table}', name: '.findallcontain')]
    public function findAllContain(string $champ, Request $request, string $table=""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValue($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        
        return $this->render("admin_playlists/index.html.twig", [
            'playlists' => $playlists,
            'categories' => $categories,            
            'valeur' => $valeur,
            'table' => $table
        ]);
    }  

    #[Route('/ajout', name: '.ajout')]
    public function ajout(Request $request): Response
    {
        $playlist = new Playlist();
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin_playlists');
        }

        return $this->render('admin_playlists/ajout.html.twig', [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView()
        ]);
    }

    #[Route('/edit/{id}', name: '.edit')]
    public function edit(Playlist $playlist, Request $request): Response
    {
        $formPlaylist = $this->createForm(PlaylistType::class, $playlist);
        
        $formPlaylist->handleRequest($request);
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist);
            return $this->redirectToRoute('admin_playlists');
        }

        return $this->render('admin_playlists/edit.html.twig', [
            'playlist' => $playlist,
            'formplaylist' => $formPlaylist->createView(),
            'formations' => $playlist->getFormations() 
        ]);
    }

    #[Route('/suppr/{id}', name: '.suppr')]
    public function suppr(Playlist $playlist): Response
    {
        if ($playlist->getFormations()->count() > 0) {
            $this->addFlash('danger', 'Impossible de supprimer cette playlist car elle contient des formations.');
            return $this->redirectToRoute('admin_playlists');
        }

        $this->playlistRepository->remove($playlist);
        return $this->redirectToRoute('admin_playlists');
    }
}