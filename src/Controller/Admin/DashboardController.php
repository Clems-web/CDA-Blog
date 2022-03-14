<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(): Response
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($adminUrlGenerator->setController(UserCrudController::class));
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CDA Blog');
    }

    public function configureMenuItems(): iterable
    {
            yield MenuItem::linkToCrud('User', 'fa fa-user', User::class);
            yield MenuItem::linkToCrud('Article', 'fas fa-book', Article::class);
            yield MenuItem::linkToCrud('Comment', 'fa fa-comment', Comment::class);
    }
}
