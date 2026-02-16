<?php
namespace App\Controller\Admin;

use App\Entity\Categorie;
use App\Form\CategorieType;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AdminCategorieController
 *
 * @author toled
 */
class AdminCategorieController extends AbstractController{

    
    const PAGE_CATEGORIES = "admin/categories.html.twig";
    
    const PAGE_CATEGORIE = "admin/categorie.html.twig";

    /**
     * 
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    /**
     * 
     * @param CategorieRepository $categorieRepository
     */
    public function __construct(CategorieRepository $categorieRepository) {
        $this->categorieRepository = $categorieRepository;
    }
    
    #[Route('/admin/categories', name: 'admin.categories')]
    public function index(): Response {
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_CATEGORIES, [
            'categories' => $categories
        ]);
    }   
    
    #[Route('/admin/categories/delete/{id}', name: 'admin.categories.delete')]
    public function suppr(int $id): Response{
        $categorie = $this->categorieRepository->find($id);
        if (!$categorie) {
            $this->addFlash('warning', 'Categorie introuvable.');
            return $this->redirectToRoute('admin.categories');
        }
        if (!$categorie->getFormations()->isEmpty()){
            $this->addFlash('warning', 'Impossible de supprimer la categire ' .$categorie->getName() .', car elle contient des formations.');
            return $this->redirectToRoute('admin.categories');
        }
        $this->categorieRepository->remove($categorie, true);
        $this->addFlash('danger', 'La suppression de la formation "' . $categorie->getName() . '" a été effectuée avec succès.');

        return $this->redirectToRoute('admin.categories');
    }

    
    #[Route('/admin/categories/add', name: 'admin.categories.add')]
    public function ajout(Request $request): Response{
        $categorie = new Categorie;
            $form = $this->createForm(CategorieType::class, $categorie);
            $form->handleRequest($request);
            
            if($form->isSubmitted() && $form->isValid()){
                    $this->categorieRepository->add($categorie, true );
                    $this->addFlash(
                            'success',
                            'Ajout de la categorie ' . $categorie->getName() . ' prise en compte');
                    return $this->redirectToRoute('admin.categories');
            }
            return $this->render(self::PAGE_CATEGORIE, [
                'categorie' => $categorie,
                'formCategorie' => $form->createView()
             ]);
       }
    
}
