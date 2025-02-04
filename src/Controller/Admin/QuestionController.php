<?php

namespace App\Controller\Admin;

use App\Entity\Answer;
use DateTimeImmutable;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use App\Repository\SubCategoryRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class QuestionController extends AbstractController
{
    #[Route('/admin/question', name: 'question_view')]
    public function questionView(): Response
    {
        return $this->render('admin/question.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/admin/question/add', name: 'question_add')]
    public function categoryAdd(Request $request, EntityManagerInterface $em, SubCategoryRepository $subCategoryRepo): Response
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        // dd($data);

        if (empty($data['question']['text'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez écrire une question.'
            ], Response::HTTP_BAD_REQUEST);    
        }
        if (empty($data['question']['type'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez choisir entre question à réponse multiple ou Vrai/Faux.'
            ], Response::HTTP_BAD_REQUEST);    
        }
        if (empty($data['question']['category']['id'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez choisir une catégorie.'
            ], Response::HTTP_BAD_REQUEST);    
        } 
        if (empty($data['question']['subCategory']['id'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez choisir une sous catégorie.'
            ], Response::HTTP_BAD_REQUEST);    
        }

        $isTrueAnswers = []; 
        foreach ($data['question']['answers'] as $answer) {
            if (empty($answer['text']) && $data['question']['type'] === 'multiple'){
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Veuillez remplir toute les réponses, si présentes.'
                ], Response::HTTP_BAD_REQUEST);
            }
            array_push($isTrueAnswers, $answer['isTrue']);
        }

        if (!in_array(true, $isTrueAnswers, true) && $data['question']['type'] === 'multiple'){
            return new JsonResponse([
                'success' => false,
                'message' => 'Il faut au moins une réponse valide.'
            ], Response::HTTP_BAD_REQUEST);
        } 

        $question = new Question();
        $subCategory = $subCategoryRepo->find($data['question']['subCategory']['id']);

        $question
        ->setText($data['question']['text'])
        ->setType($data['question']['type'])
        ->setSubCategory($subCategory)
        ->setCreatedBy($user)
        ->setCreatedAt(new DateTimeImmutable());
        
        $subCategory->addQuestion($question);

        foreach ($data['question']['answers'] as $dataAnswer) {
            $answer = new Answer();
            $answer
                ->setText($dataAnswer['text'])
                ->setIsTrue($dataAnswer['isTrue'])
                ->setCreatedBy($user)
                ->setCreatedAt(new DateTimeImmutable());
                $em->persist($answer);
                $question->addAnswer($answer);
        }

        $em->persist($subCategory);
        $em->persist($question);

        // dd($question);
        $em->flush();

        return $this->json([
            'success' => true,
            'question' => $question,
            'message' => 'La question a bien été créé.'
        ], Response::HTTP_CREATED,[],  ['groups' => 'question-read']);
    }

    #[Route('/admin/question/edit', name: 'question_edit')]
    public function categoryEdit(
        Request $request, 
        EntityManagerInterface $em, 
        QuestionRepository $questionRepo, 
        SubCategoryRepository $subCategoryRepo): Response
    {
        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);
        
        $questionProxy = $questionRepo->find($data['question']['id']);

        if (empty($data['question']['text'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez écrire une question.'
            ], Response::HTTP_BAD_REQUEST);    
        }
        if (empty($data['question']['type'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez choisir entre question à réponse multiple ou Vrai/Faux.'
            ], Response::HTTP_BAD_REQUEST);    
        }
        if (empty($data['question']['category']['id'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez choisir une catégorie.'
            ], Response::HTTP_BAD_REQUEST);    
        } 
        if (empty($data['question']['subCategory']['id'])) {
            return new JsonResponse([
                'success' => false,
                'message' => 'Veuillez choisir une sous catégorie.'
            ], Response::HTTP_BAD_REQUEST);    
        }

        $isTrueAnswers = []; 
        foreach ($data['question']['answers'] as $answer) {
            if (empty($answer['text']) && $data['question']['type'] === 'multiple'){
                return new JsonResponse([
                    'success' => false,
                    'message' => 'Veuillez remplir toute les réponses, si présentes.'
                ], Response::HTTP_BAD_REQUEST);
            }
            array_push($isTrueAnswers, $answer['isTrue']);
        }

        if (!in_array(true, $isTrueAnswers, true) && $data['question']['type'] === 'multiple'){
            return new JsonResponse([
                'success' => false,
                'message' => 'Il faut au moins une réponse valide.'
            ], Response::HTTP_BAD_REQUEST);
        } 

        $questionProxy
            ->setText($data['question']['text'])
            ->setType($data['question']['type']);
        
        if ($questionProxy->getSubCategory()->getId() !== $data['question']['subCategory']['id']){
            $questionProxy->getSubCategory()->removeQuestion($questionProxy);
            $newSubCategory = $subCategoryRepo->find($data['question']['subCategory']['id']);
            $newSubCategory->addQuestion($questionProxy);
        } 

        
        foreach ($data['question']['answers']  as $dataAnswer) {
            if ($dataAnswer['id'] === null){
                $answer = new Answer();
                $answer
                    ->setText($dataAnswer['text'])
                    ->setIsTrue($dataAnswer['isTrue'])
                    ->setCreatedBy($user)
                    ->setCreatedAt(new DateTimeImmutable());
                    $em->persist($answer);
                    $questionProxy->addAnswer($answer);  
            }
            foreach ($questionProxy->getAnswers() as $answerProxy) {
                if($dataAnswer['id'] === $answerProxy->getId()){
                    $answerProxy
                    ->setText($dataAnswer['text'])
                    ->setIsTrue($dataAnswer['isTrue'])
                    ->setUpdatedBy($user)
                    ->setUpdatedAt(new DateTimeImmutable());

                    $em->persist($answerProxy);
                } 
                
            }
        }

        $em->persist($questionProxy);
        $em->flush();

        return $this->json([
            'success' => true,
            'question' => $questionProxy,
            'message' => 'La question a bien été mis à jour.'
        ], Response::HTTP_OK,[],  ['groups' => 'question-read']);
    }

    #[Route('/admin/question/list', name: 'question_list')]
    public function questionList(Request $request, QuestionRepository $questionRepo): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $filters = $request->query->all();

        $result = $questionRepo->getPaginatedQuestions($page, $limit, $filters);
        // dd($result);
        return $this->json(['result' => $result], 200, [],  ['groups' => 'question-read']);
    }
}
