<?php

namespace App\Form;

use App\Entity\User;
use Omines\DataTablesBundle\Adapter\Doctrine\ORMAdapter;
use Omines\DataTablesBundle\Column\NumberColumn;
use Omines\DataTablesBundle\DataTable;
use Omines\DataTablesBundle\Column\TextColumn;
use Omines\DataTablesBundle\DataTableTypeInterface;

class ListUsersForm implements DataTableTypeInterface
{
    public function configure(DataTable $dataTable, array $options)
    {
        $dataTable
            ->add('id', NumberColumn::class, ['label' => 'id', 'searchable' => false, 'visible' => false])
            ->add('firstName', TextColumn::class, ['label' => 'First name', 'searchable' => true])
            ->add('lastName', TextColumn::class, ['label' => 'Last name', 'searchable' => true])
            ->add('email', TextColumn::class, ['label' => 'Email', 'searchable' => true])
            ->createAdapter (ORMAdapter::class, [
                'entity' => User::class,
                'query' => function ($builder) {
                    $builder
                        ->select('u')
                        ->from(User::class, 'u')
                        ->where('u.roles = :role')
                        ->setParameter('role', 'ROLE_USER');
                },
            ]);
    }
}