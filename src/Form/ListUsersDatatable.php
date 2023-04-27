<?php

namespace App\Form;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\DataTableFactory;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ListUsersDatatable implements DataTableTypeInterface
{
    public function __construct(private readonly DataTableFactory $dataTableFactory,
                                private readonly EntityManagerInterface $em,
                                private readonly UserRepository $userRepository)
    {
    }

    public function configure(DataTable $dataTable, array $options)
    {

//        $db = $this->userRepository->findAll();

//        $table = $this->dataTableFactory->create()
//            ->add('firstName', TextColumn::class, ['label' => 'firstName'])
//            ->add('lastName', TextColumn::class, ['label' => 'lastName'])
//            ->add('email', TextColumn::class, ['label' => 'email'])
//            ->add('roles', TextColumn::class, ['label' => 'roles']) //tostring
//            ->createAdapter(ORMAdapter::class,[
//                'entity' => User::class,
//                'query' => function (QueryBuilder $queryBuilder) {
//                    $queryBuilder
//                        ->select('q')
//                        ->from(User::class, 'q')
//                    ;
//                }
//            ]);
    }

}