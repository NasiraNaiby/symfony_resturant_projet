<?php

namespace App\Controller\Admin;

use App\Entity\Users;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;



class UsersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Users::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Ultilisateurs')
            ->setEntityLabelInSingular('Ultilisateur')
            ->setPageTitle('index','Admin panel');
    }

    


    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // ID field
            TextField::new('user_nom', "Nom d'utilisateur"), // User name
            TextField::new('email', 'Email'),
            ChoiceField::new('roles', 'Roles')
            ->setChoices([
                'Admin' => 'ROLE_ADMIN',
                'User' => 'ROLE_USER',
            ])
            ->allowMultipleChoices(true) // Allow selecting multiple roles
            ->renderExpanded(false), // Render as a dropdown
            TextField::new('tel', 'Telephone'),
            TextField::new('addresse', 'Address'),
            ImageField::new('user_photo', 'Photo')
                ->setUploadDir('public/uploads/') // Directory where images are stored
                ->setBasePath('uploads/') // Path used to display images in the UI
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(true), // Mark the field as required
        ];
    }
    
    
    
    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
