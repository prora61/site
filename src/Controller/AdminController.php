<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\CreateUserForm;
use App\Form\EditUserForm;
use App\Model\CreateUserModel;
use App\Services\AdminService;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly AdminService $adminService
    ) {
    }

    #[Route(path: '/admin', name: 'admin_start')]
    public function index(): Response
    {
//                $str = 'wertyuiopsdfghcxjvbnm';
//                for ($i=0; $i < 10; $i++){
//                    $user = new User();
//                    $user->setFirstName('elala')
//                        ->setLastName('wfas')
//                        ->setRoles(['ROLE_USER'])
//                        ->setEmail(str_shuffle($str).'@mail.ru');
//                    $user->setPassword('testpas');
//                    $this->em->persist($user);
//                }
//
//                $this->em->flush();

        return $this->json('null');
    }

    #[Route(path: '/admin/users', name: 'user_list')]
    public function listUsers(Request $request, DataTableFactory $dataTableFactory): Response
    {
//        $table = $dataTableFactory
//            ->createFromType(ListUsersDatatable::class)
//            ->handleRequest($request);

        $table = $dataTableFactory->create()
            ->add('id', NumberColumn::class, ['label' => 'id', 'searchable' => false, 'visible' => false])
            ->add('firstName', TextColumn::class, ['label' => 'firstName', 'searchable' => true])
            ->add('lastName', TextColumn::class, ['label' => 'lastName', 'searchable' => true])
            ->add('email', TextColumn::class, ['label' => 'email', 'searchable' => true])
            ->createAdapter (ORMAdapter::class, [
                'entity' => User::class,
                'query' => function ($builder) {
                    $builder
                        ->select('u')
                        ->from(User::class, 'u')
                        ->where('u.roles = :role')
                        ->setParameter('role', 'ROLE_USER');
                },
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/list_users.html.twig', ['datatable' => $table]);
    }

    #[Route(path: '/admin/users/delete', name: 'delete_user', methods: ['DELETE'])]
    public function deleteUser(Request $request): Response
    {
        $id = $request->request->get('id');
        $response = $this->adminService->deleteUser($id);

        return $this->json($response);
    }

    #[Route(path: '/admin/users/create', name: 'create_user', methods: ['GET', 'POST'])]
    public function createUser(Request $request): Response
    {
        $user = new CreateUserModel();
        $form = $this->createForm(CreateUserForm::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->adminService->createUser($user);
            return $this->json($response);
//                return $this->redirectToRoute('user_list');
        }

        return $this->render('admin/create_user.html.twig', [
            'createUserForm' => $form->createView(),
        ],
        );
    }

    #[Route(path: '/admin/users/update', name: 'update_user_list', methods: ['GET', 'POST'])]
    public function updateUserList(Request $request): Response
    {
        $id = $request->query->get('id');
        $user = $this->adminService->map($id);
        $form = $this->createForm(EditUserForm::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $response = $this->adminService->updateUser($user);
            if ($response['success'] === true) {
                return $this->redirectToRoute('user_list');
            }
            else {
                return $this->json($response);
            }
        }

        return $this->render('admin/edit_user.html.twig', [
            'editUserForm' => $form->createView(),
        ],
        );
    }
}
