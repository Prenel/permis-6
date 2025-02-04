<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use App\Entity\Category;
use App\Entity\SubCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;   

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    

    public function load(ObjectManager $manager) 
    { 
        $user = $this->getReference('admin_user');

        // Listes de noms prédéfinis pour éviter les doublons
        $categoryNames = [ 
            "Gestion des Incidents", 
            "Gestion des Problèmes", 
            "Gestion des Changements", 
            "Gestion des Configurations (CMDB)", 
            "Gestion des Releases", 
            "Gestion des Capacités", 
            "Surveillance des Performances", 
            "Monitoring Applicatif", 
            "Supervision Réseau", 
            "Gestion des Logs", 
            "Automatisation des Déploiements", 
            "Gestion des Backups", 
            "Plan de Continuité d'Activité (PCA)", 
            "Plan de Reprise d'Activité (PRA)", 
            "Gestion des Permissions", 
            "Sécurité des Applications", 
            "Sécurité des Systèmes", 
            "Gestion des Vulnérabilités", 
            "Patch Management", 
            "Audit et Conformité", 
            "Gestion des Accès", 
            "Gestion des Identités", 
            "Gestion des Services Cloud", 
            "Infrastructure as Code (IaC)", 
            "Orchestration des Conteneurs", 
            "Gestion des Microservices", 
            "Déploiement CI/CD", 
            "Gestion des Environnements de Test", 
            "Gestion des Environnements de Préproduction", 
            "Documentation Technique", 
            "Suivi des SLA et KPI", 
            "Gestion des Fournisseurs IT", 
            "Gestion des Licences", 
            "Gestion des Versions", 
            "Support et Helpdesk", 
            "Gestion des Tickets", 
            "Gestion des Bases de Données", 
            "Optimisation des Performances SQL", 
            "Gestion des API", 
            "Sécurisation des API", 
            "Gestion des Certificats SSL", 
            "Surveillance des Temps de Réponse", 
            "Gestion des Ressources Cloud", 
            "Optimisation des Coûts IT", 
            "Gestion des Virtualisations", 
            "Gestion des Hyperviseurs", 
            "Gestion des Sauvegardes Cloud", 
            "Surveillance des Erreurs Applicatives", 
            "Gestion des Dépendances Logicielles", 
            "Mise en conformité RGPD" 
        ];  
        $subCategoryNames = ["Avancé", "Débutant", "Intermédiaire", "Pro", "Niveau 1", "Niveau 2", "Niveau 3", "Niveau 4", "Expert", "Spécial"]; 
        for ($i = 0; $i < 15; $i++) { 
            // Sélectionne un nom unique de catégorie et l'ajoute pour ne pas le réutiliser 
            $categoryKey = array_rand($categoryNames); $categoryName = $categoryNames[$categoryKey]; 
            // Ajoute un identifiant unique 
            unset($categoryNames[$categoryKey]); 
            // Empêche la réutilisation du nom 
            $category = new Category(); 
            $category->setName($categoryName);
            $category->setCreatedBy($user);
            $category->setCreatedAt(new DateTimeImmutable());
            
            $manager->persist($category);

            // Générer entre 2 et 5 sous-catégories 
            $numSubCategories = rand(2, 5); 
            for ($j = 0; $j < $numSubCategories; $j++) {
                $subCategoryKey = array_rand($subCategoryNames); 
                $subCategoryName = $subCategoryNames[$subCategoryKey] . " " . uniqid(); 
                $subCategory = new SubCategory(); 
                $subCategory->setName($subCategoryName); 
                $subCategory->setCategory($category);
                $subCategory->setCreatedBy($user);
                $subCategory->setCreatedAt(new DateTimeImmutable()); 
                $manager->persist($subCategory); 
            } 
        } 
        $manager->flush(); 

    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    } 

}
