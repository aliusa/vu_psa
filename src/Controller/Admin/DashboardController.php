<?php

namespace App\Controller\Admin;

use App\Entity\Administrators;
use App\Entity\Config;
use App\Entity\Countries;
use App\Entity\Invoices;
use App\Entity\Questions;
use App\Entity\QuestionsCategories;
use App\Entity\Services;
use App\Entity\ServicesCategories;
use App\Entity\ServicesPromotions;
use App\Entity\Structures;
use App\Entity\Users;
use App\Entity\UsersObjects;
use App\Entity\UsersObjectsServices;
use App\Entity\UsersObjectsServicesBundles;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    )
    {
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addJsFile('public/vendor/leaflet/dist/leaflet.js')
            ->addCssFile('public/vendor/leaflet/dist/leaflet.css')
            ->addJsFile('public/admin/js/leaflet.js')
            ;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.twig', [
            //'dashboard_controller_filepath' => (new \ReflectionClass(static::class))->getFileName(),
            'userCount' => $this->entityManager->getRepository(Users::class)->count(),
            'activeUsersServicesBundlesCount' => $this->entityManager->getRepository(UsersObjectsServicesBundles::class)
                ->createQueryBuilder('item')
                ->select('COUNT(1)')
                ->andWhere('item.active_to >= CURRENT_TIMESTAMP() OR item.active_to IS NULL')
                ->getQuery()->getSingleScalarResult(),
            'unpaidAmount' => $this->entityManager->getRepository(Invoices::class)
                ->createQueryBuilder('invoices')
                ->select('SUM(users_objects_services.total_price) AS total')
                ->innerJoin('invoices.users_objects_services_bundles', 'users_objects_services_bundles')/** @see Invoices::$users_objects_services_bundles */
                ->innerJoin('users_objects_services_bundles.users_objects_services', 'users_objects_services')/** @see UsersObjectsServicesBundles::$users_objects_services */
                ->andWhere('invoices.is_paid IS NULL')
                ->getQuery()
                ->getSingleScalarResult(),
            'coordinates' => $this->entityManager->getRepository(UsersObjects::class)
                ->createQueryBuilder('item')
                ->select('item.coordinates')
                ->andWhere('item.coordinates IS NOT NULL')
                ->getQuery()->getSingleColumnResult(),
        ]);
        return $this->render('@EasyAdmin/welcome.html.twig', [
            'dashboard_controller_filepath' => (new \ReflectionClass(static::class))->getFileName(),
        ]);
        return parent::index();
        // redirect to some CRUD controller
        //$routeBuilder = $this->container->get(AdminUrlGenerator::class);
        //return $this->redirect($routeBuilder->setController(UsersCrudController::class)->generateUrl());

        // you can also render some template to display a proper Dashboard
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        return $this->render('some/path/my-dashboard.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            // the name visible to end users
            ->setTitle('VU PSA')
            // you can include HTML contents too (e.g. to link to an image)
            ->setTitle('<img src="/public/logo.png" style="width: 50px; height: auto;" alt="logo"/>')

            // the path defined in this method is passed to the Twig asset() function
            ->setFaviconPath('/public/icons/favicon.png')

            // the domain used by default is 'messages'
            ->setTranslationDomain('messages')

            // there's no need to define the "text direction" explicitly because
            // its default value is inferred dynamically from the user locale
            ->setTextDirection('ltr')

            // set this option if you prefer the page content to span the entire
            // browser width, instead of the default design which sets a max width
            ->renderContentMaximized()

            // set this option if you prefer the sidebar (which contains the main menu)
            // to be displayed as a narrow column instead of the default expanded design
            //->renderSidebarMinimized()

            // by default, all backend URLs include a signature hash. If a user changes any
            // query parameter (to "hack" the backend) the signature won't match and EasyAdmin
            // triggers an error. If this causes any issue in your backend, call this method
            // to disable this feature and remove all URL signature checks
            ->disableUrlSignatures()

            // by default, all backend URLs are generated as absolute URLs. If you
            // need to generate relative URLs instead, call this method
            ->generateRelativeUrls()
            ;
    }

    public function configureMenuItems(): iterable
    {
        //yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);


        return [
            MenuItem::linkToDashboard('Pradinis', 'fa fa-home'),

            //MenuItem::section('Blog'),
            //MenuItem::linkToCrud('Categories', 'fa fa-tags', Category::class),
            //MenuItem::linkToCrud('Blog Posts', 'fa fa-file-text', BlogPost::class),

            //MenuItem::section('Users'),
            //MenuItem::linkToCrud('Comments', 'fa fa-comment', Comment::class),
            MenuItem::section('Klientai'),
            MenuItem::linkToCrud('Klientai', 'fa fa-user', Users::class),
            MenuItem::linkToCrud('Klientų objektai', 'fa-solid fa-house', UsersObjects::class),
            MenuItem::linkToCrud('Klientų objektų paslaugų paketai', 'fa-solid fa-cubes', UsersObjectsServicesBundles::class),
            MenuItem::linkToCrud('Klientų objektų paslaugų paketo paslaugos', 'fa-solid fa-cube', UsersObjectsServices::class),
            MenuItem::linkToCrud('Sąskaitos', 'fa-solid fa-receipt', Invoices::class),
            MenuItem::section('Paslaugos'),
            MenuItem::linkToCrud('Paslaugos', 'fa-solid fa-shop', Services::class),
            MenuItem::linkToCrud('Paslaugų kategorijos', 'fa-solid fa-layer-group', ServicesCategories::class),
            MenuItem::linkToCrud('Akcijos', 'fa-solid fa-percent', ServicesPromotions::class),
            MenuItem::section('Klausimai'),
            MenuItem::linkToCrud('Klausimai', 'fa-regular fa-circle-question', Questions::class),
            MenuItem::linkToCrud('Klausimų kategorijos', 'fa-solid fa-layer-group', QuestionsCategories::class),
            MenuItem::section('Administratoriai'),
            MenuItem::linkToCrud('Administratoriai', 'fas fa-user-shield', Administrators::class),
            MenuItem::linkToCrud('Šalys', 'fa-solid fa-globe', Countries::class),
            MenuItem::section('Nustatymai'),
            MenuItem::linkToCrud('Struktūra', 'fa-solid fa-folder-tree', Structures::class),
            MenuItem::linkToCrud('Nustatymai', 'fa-solid fa-wrench', Config::class),
        ];

        //yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
