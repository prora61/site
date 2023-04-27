<?php

namespace App\Controller;

use App\Entity\User;
use Omines\DataTablesBundle\Adapter\ArrayAdapter;
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
    public function __construct()
    {
    }

    #[Route(path: '/admin', name: 'admin_start')]
    public function index(): Response
    {
        return $this->json('null');
    }

    #[Route(path: '/admin/users')]
    public function listUsers(Request $request, DataTableFactory $dataTableFactory): Response
    {
        //            $table = $dataTableFactory
        //                ->createFromType(ListUsersDatatable::class)
        //                ->handleRequest($request);

        $table = $dataTableFactory->create()
            ->add('id', NumberColumn::class, ['label' => 'id'])
            ->add('firstName', TextColumn::class, ['label' => 'firstName'])
            ->add('lastName', TextColumn::class, ['label' => 'lastName'])
            ->add('email', TextColumn::class, ['label' => 'email'])
//            ->add('roles', TextColumn::class, ['label' => 'roles']) //tostring
            ->createAdapter(ArrayAdapter::class, [
                ['firstName' => 'Donald', 'lastName' => 'Trump'],
                ['firstName' => 'Barack', 'lastName' => 'Obama'],
            ])
            ->createAdapter(ORMAdapter::class, [
                'entity' => User::class,
                'query' => function ($builder) {
                    $builder
                        ->select('u')
                        ->from(User::class, 'u');
                },
            ])
            ->handleRequest($request);

        if ($table->isCallback()) {
            return $table->getResponse();
        }

        return $this->render('admin/list_users.html.twig', ['datatable' => $table]);
    }
}
