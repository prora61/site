<?php

namespace App\Controller;

use App\Entity\User;
use App\Exception\UserAlreadyExistsByEmailException;
use App\Form\CreateUserForm;
use App\Model\CreateUserModel;
use App\Services\AdminService\CreateUserService;
use Doctrine\ORM\EntityManagerInterface;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly CreateUserService $createUserService
    ) {
    }

    #[Route(path: '/admin', name: 'admin_start')]
    public function index(): Response
    {
        //        $str = 'wertyuiopsdfghcxjvbnm';
        //        for ($i=0; $i < 101; $i++){
        //            $user = new User();
        //            $user->setFirstName('test')
        //                ->setLastName('test')
        //                ->setRoles(['ROLE_USER'])
        //                ->setEmail(str_shuffle($str).'@mail.ru');
        //            $user->setPassword('testpas');
        //            $this->em->persist($user);
        //        }
        //
        //        $this->em->flush();
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
            ->createAdapter(ORMAdapter::class, [
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
//        $data = $request->getContent();

        $response = array('success' => true);

        return $this->json($response);
    }

    #[Route(path: '/admin/users/create', name: 'create_user')]
    public function createUser(Request $request): Response
    {
        $user = new CreateUserModel();
        $form = $this->createForm(CreateUserForm::class, $user);
        $form->handleRequest($request);

        try{
            if ($form->isSubmitted() && $form->isValid()) {
                $this->createUserService->mapAndSave($user);
//                return $this->redirectToRoute('user_list');
            }
        } catch (UserAlreadyExistsByEmailException $e){
            echo $e->getMessage();
        }

        return $this->render('admin/create_user.html.twig', [
            'createUserForm' => $form->createView(),
        ],
        );
    }
}
