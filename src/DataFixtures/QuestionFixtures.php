<?php

namespace App\DataFixtures;

use DateTimeImmutable;
use App\Entity\Question;
use App\Entity\Answer;
use App\Entity\Category;
use App\Entity\SubCategory;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference('admin_user');

        // Sélection de 5 ou 6 catégories existantes (ID de 1 à 50)
        $categoryIds = range(1, 50);
        shuffle($categoryIds);
        $selectedCategories = array_slice($categoryIds, 0, rand(5, 6));

        // Définition des questions avec leurs réponses correctes et incorrectes
        $questions = [
            [
                "text" => "Qu'est-ce que la gestion des incidents ?",
                "type" => "open",
                "answers" => [
                    ["text" => "Processus de gestion des événements perturbateurs", "isTrue" => true],
                    ["text" => "Un document de sécurité", "isTrue" => false],
                    ["text" => "Une simple notification aux utilisateurs", "isTrue" => false],
                    ["text" => "Un système d'alerte automatisé", "isTrue" => true]
                ]
            ],
            [
                "text" => "Comment gérer un changement en production ?",
                "type" => "open",
                "answers" => [
                    ["text" => "Évaluer l'impact et tester avant déploiement", "isTrue" => true],
                    ["text" => "Déployer directement sans vérification", "isTrue" => false],
                    ["text" => "Faire une sauvegarde et rollback possible", "isTrue" => true]
                ]
            ],
            [
                "text" => "Les SLA définissent-ils les niveaux de service obligatoires ?",
                "type" => "true_false",
                "answers" => [
                    ["text" => "", "isTrue" => true]
                ]
            ],
            [
                "text" => "Quels sont les avantages du monitoring applicatif ?",
                "type" => "open",
                "answers" => [
                    ["text" => "Détecter et prévenir les pannes", "isTrue" => true],
                    ["text" => "Augmenter la charge du système inutilement", "isTrue" => false],
                    ["text" => "Optimiser la performance des applications", "isTrue" => true]
                ]
            ],
            [
                "text" => "Une bonne gestion des vulnérabilités réduit-elle les cyberattaques ?",
                "type" => "true_false",
                "answers" => [
                    ["text" => "", "isTrue" => true]
                ]
            ],
            [
                "text" => "Quels sont les niveaux de sauvegarde des données ?",
                "type" => "open",
                "answers" => [
                    ["text" => "Sauvegarde complète", "isTrue" => true],
                    ["text" => "Sauvegarde différentielle", "isTrue" => true],
                    ["text" => "Sauvegarde séquentielle", "isTrue" => false],
                    ["text" => "Sauvegarde incrémentale", "isTrue" => true]
                ]
            ],
            [
                "text" => "Un PCA permet-il de continuer l'activité en cas de panne majeure ?",
                "type" => "true_false",
                "answers" => [
                    ["text" => "", "isTrue" => true]
                ]
            ],
            [
                "text" => "Quelle est la différence entre CI/CD et un déploiement manuel ?",
                "type" => "open",
                "answers" => [
                    ["text" => "CI/CD automatise les tests et déploiements", "isTrue" => true],
                    ["text" => "Le déploiement manuel est plus rapide", "isTrue" => false],
                    ["text" => "CI/CD permet des mises à jour fréquentes", "isTrue" => true]
                ]
            ],
            [
                "text" => "L'infrastructure as Code (IaC) permet-elle d'automatiser les déploiements ?",
                "type" => "true_false",
                "answers" => [
                    ["text" => "", "isTrue" => true]
                ]
            ],
            [
                "text" => "Quels sont les avantages d'une supervision réseau efficace ?",
                "type" => "open",
                "answers" => [
                    ["text" => "Identification rapide des problèmes", "isTrue" => true],
                    ["text" => "Diminution des performances globales", "isTrue" => false],
                    ["text" => "Optimisation des coûts IT", "isTrue" => true]
                ]
            ],
            [
                "text" => "Un audit IT vise-t-il à identifier les failles de sécurité ?",
                "type" => "true_false",
                "answers" => [
                    ["text" => "", "isTrue" => true]
                ]
            ],
            [
                "text" => "Comment sécuriser une API ?",
                "type" => "open",
                "answers" => [
                    ["text" => "Utiliser OAuth pour l'authentification", "isTrue" => true],
                    ["text" => "Désactiver toutes les restrictions de requêtes", "isTrue" => false],
                    ["text" => "Restreindre l'accès avec des tokens", "isTrue" => true]
                ]
            ]
        ];

        foreach ($questions as $q) {
            // Sélectionner une catégorie et une sous-catégorie aléatoire
            $categoryId = $selectedCategories[array_rand($selectedCategories)];
            $category = $manager->getRepository(Category::class)->find($categoryId);

            if (!$category) {
                continue; // Sécurité, mais ça ne devrait pas arriver
            }

            $subCategories = $category->getSubCategories();
            if ($subCategories->isEmpty()) {
                continue; // Si la catégorie n'a pas de sous-catégories, on saute cette itération
            }

            $subCategory = $subCategories[array_rand($subCategories->toArray())];

            // Création de la question
            $question = new Question();
            $question->setText($q["text"]);
            $question->setCreatedAt(new DateTimeImmutable());
            $question->setCreatedBy($user);
            $question->setSubCategory($subCategory);
            $manager->persist($question);

            // Ajout des réponses
            foreach ($q["answers"] as $answerData) {
                $answer = new Answer();
                $answer->setText($answerData["text"]);
                $answer->setIsTrue($answerData["isTrue"]);
                $answer->setCreatedAt(new DateTimeImmutable());
                $answer->setCreatedBy($user);
                $answer->setQuestion($question);
                $manager->persist($answer);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }
}

