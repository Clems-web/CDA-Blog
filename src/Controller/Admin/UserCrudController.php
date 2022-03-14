<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('username'),
            TextField::new('email'),
            ChoiceField::new('roles')
                ->allowMultipleChoices()
                ->setChoices([
                    'user' => 'ROLE_USER',
                    'author' => 'ROLE_AUTHOR',
                    'moderator' => 'ROLE_MODERATOR',
                    'admin' => 'ROLE_ADMIN',

                ])
        ];
    }

}
