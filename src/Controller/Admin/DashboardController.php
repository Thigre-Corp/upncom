<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Article;
use App\Entity\Contact;
use App\Entity\Service;
use App\Entity\Newsletters\Newsletter;
use App\Entity\Newsletters\Subscriber;
use Symfony\Component\AssetMapper\AssetMapper;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[AdminDashboard(routePath: '/eza2min', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/my-dashboard.html.twig');
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // 1.1) If you have enabled the "pretty URLs" feature:
        // return $this->redirectToRoute('admin_user_index');
        //
        // 1.2) Same example but using the "ugly URLs" that were used in previous EasyAdmin versions:
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirectToRoute('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Up\'n\'Com');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::section('Contacts');
        yield MenuItem::linkToCrud('Contacts', 'fas fa-phone', Contact::class);

        yield MenuItem::section('Blog');
        yield MenuItem::linkToCrud('Articles', 'fas fa-blog', Article::class);
        yield MenuItem::linkToCrud('Tags', 'fas fa-tag', Tag::class);
        yield MenuItem::linkToCrud('Users', 'fas fa-user', User::class);
 
        yield MenuItem::section('Services');
        yield MenuItem::linkToCrud('Services', 'fas fa-euro', Service::class);

        yield MenuItem::section('NewsLetter');
        yield MenuItem::linkToCrud('Liste des inscrits', 'fas fa-users', Subscriber::class);
        yield MenuItem::linkToCrud('Newsletters', 'fas fa-newspaper', Newsletter::class);
    
        yield MenuItem::section('Images');
        yield MenuItem::linkToRoute('Ajouter des images', 'fas fa-images', 'app_image');
        yield MenuItem::linkToCrud('Image', 'fas fa-image', Image::class);
    }
    

    /*
        Ajout des assets css/js (stimulus UX dropzone...)
    */
    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addAssetMapperEntry('app');
        ;
    }
}
