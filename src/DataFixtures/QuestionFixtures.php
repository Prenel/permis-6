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
    public function load(ObjectManager $manager): void
    {
        $user = $this->getReference('admin_user');

        // 🔹 Récupérer toutes les catégories existantes
        $categories = $manager->getRepository(Category::class)->findAll();
        if (empty($categories)) {
            dump("⚠️ Aucune catégorie trouvée !");
            return;
        }

        // 🔹 Sélectionner 5 ou 6 catégories aléatoires
        shuffle($categories);
        $selectedCategories = array_slice($categories, 0, rand(5, 6));

        // 🔹 Définition des questions et réponses
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
                "text" => "Un PCA permet-il de continuer l'activité en cas de panne majeure ?",
                "type" => "true_false",
                "answers" => [
                    ["text" => "", "isTrue" => true]
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
                "text" => "Comment sécuriser une API ?",
                "type" => "open",
                "answers" => [
                    ["text" => "Utiliser OAuth pour l'authentification", "isTrue" => true],
                    ["text" => "Désactiver toutes les restrictions de requêtes", "isTrue" => false],
                    ["text" => "Restreindre l'accès avec des tokens", "isTrue" => true]
                ]
            ]
        ];

        foreach ($selectedCategories as $category) {
            $subCategories = $category->getSubCategories();

            // 🔹 Vérification des sous-catégories
            if ($subCategories->isEmpty()) {
                dump("⚠️ La catégorie " . $category->getName() . " n'a pas de sous-catégories !");
                continue;
            }

            $subCategory = $subCategories[array_rand($subCategories->toArray())]; // ✅ Sélection d'une sous-catégorie existante

            foreach ($questions as $q) {
                // 🔹 Création de la question
                $question = new Question();
                $question->setText($q["text"]);
                $question->setCreatedAt(new DateTimeImmutable());
                $question->setCreatedBy($user);
                $question->setSubCategory($subCategory);
                $manager->persist($question);

                dump("✅ Question ajoutée : " . $q["text"]);

                // 🔹 Ajout des réponses
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
        }

        $manager->flush();
        dump("✅ Toutes les questions et réponses ont été insérées !");
    }

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            UserFixtures::class
        ];
    }
}

