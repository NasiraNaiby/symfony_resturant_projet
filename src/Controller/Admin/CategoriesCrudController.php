<?php

namespace App\Controller\Admin;

use App\Entity\Categories;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class CategoriesCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Categories::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Categories')
            ->setEntityLabelInSingular('Categorie')
            ->setPageTitle('index','categorie panel');
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('cat_nom','title'),
            TextEditorField::new('cat_description','description'),
            ImageField::new('cat_image', 'Photo')
            ->setUploadDir('public/uploads/') // Directory where images are stored
            ->setBasePath('uploads/') // Path used to display images in the UI
            ->setUploadedFileNamePattern('[randomhash].[extension]') // Save images with unique names
            ->setRequired(true), // Mark the field as required
        ];
    }
    
}
