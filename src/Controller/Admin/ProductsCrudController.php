<?php

namespace App\Controller\Admin;

use App\Entity\Products;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;


class ProductsCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Products::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new(propertyName:'name'),
            SlugField::new(propertyName:'slug')->setTargetFieldName('name'),
            ImageField::new(propertyName:'illustration')
            ->setBasePath(path:'uploads/')
            ->setUploadDir(uploadDirPath:'public/uploads/')
            ->setUploadedFileNamePattern(patternOrCallable:'[randomhash].[extension]')
            ->setRequired(isRequired: false),
            TextField::new(propertyName:'subtitle'),
            TextareaField::new(propertyName:'description'),
            MoneyField::new(propertyName:'price')->setCurrency(currencyCode:'EUR'),
            AssociationField::new(propertyName:'category'),
        ];
    }
    
}
