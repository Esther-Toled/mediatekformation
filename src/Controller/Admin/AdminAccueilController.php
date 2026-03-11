<?php
namespace App\Controller\Admin;

use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Description of AccueilController
 *
 * @author toled
 */
class AdminAccueilController extends AbstractController{
    
    /**
     * @var FormationRepository
     */
    private $repository;
    
    /**
     * 
     * @param FormationRepository $repository
     */
    public function __construct(FormationRepository $repository) {
        $this->repository = $repository;
    }   
    /**
     * 
     * @return Response
     */
    #[Route('/admin', name: 'admin.accueil')]
    public function index(): Response{        
        return $this->render("admin/admin.accueil.html.twig"); 
    }
}
