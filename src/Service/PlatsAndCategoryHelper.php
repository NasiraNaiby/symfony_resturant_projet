<?php

namespace App\Service;

use App\Entity\Plats; // Import the Plats entity
use Doctrine\ORM\EntityManagerInterface;

class PlatsAndCategoryHelper
{
    private EntityManagerInterface $entityManager;

    // Inject the EntityManagerInterface into the service
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Method to retrieve plats by a single category name
    public function getCategoryByPlats(string $categoryName): array
{
    return $this->entityManager->createQueryBuilder()
        ->select('p.id AS id, p.plat_photo AS platPhoto, p.plat_nom AS platNom, c.cat_description AS categoryDescription, p.plat_prix AS platPrix')
        ->from(Plats::class, 'p') // Reference the Plats entity
        ->innerJoin('p.categorie', 'c') // Join the Categories entity
        ->where('c.cat_nom = :categoryName') // Filter by the category name
        ->setParameter('categoryName', $categoryName) // Dynamically set the category name
        ->getQuery()
        ->getResult();
}


    // Method to retrieve plats by a single category or multiple categories
    public function getPlatsByCategory($category, bool $multiple = false): array
    {
        $queryBuilder = $this->entityManager->getRepository(Plats::class)
            ->createQueryBuilder('p')
            ->join('p.categorie', 'c'); // Join with the Categories entity

        if ($multiple) {
            $queryBuilder->where('c.cat_nom IN (:categories)') // Use IN for multiple categories
                         ->setParameter('categories', $category); // Pass an array of categories
        } else {
            $queryBuilder->where('c.cat_nom = :category') // Use = for a single category
                         ->setParameter('category', $category); // Pass a single category
        }

        return $queryBuilder->getQuery()->getResult();
    }
}
