<?php

namespace App\Controller\Admin;

use App\Entity\Plats;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;

class PlatsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Plats::class;
    }

  
  

    

public function configureFields(string $pageName): iterable
{
    return [
        IdField::new('id')->hideOnForm(), // ID field, hidden on the form
        TextField::new('plat_nom', 'Nom du Plat'), // Plat name
        TextEditorField::new('plat_description', 'Description'), // Description
        NumberField::new('plat_prix', 'Prix'), // Price
        AssociationField::new('categorie', 'Catégorie')->setRequired(true), // Dropdown to select category
        ImageField::new('plat_photo', 'Photo')
            ->setUploadDir('public/uploads/') // Directory where images are stored
            ->setBasePath('uploads/') // Path used to display images in the UI
            ->setUploadedFileNamePattern('[randomhash].[extension]') // Save images with unique names
            ->setRequired(true), // Mark the field as required
        BooleanField::new('active', 'Actif')->renderAsSwitch(true), // Boolean toggle for active/inactive
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
