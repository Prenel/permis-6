<?php

namespace App\Controller\Admin;

use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
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

    #[Route('/admin/question/list', name: 'question_list')]
    public function questionList(Request $request, QuestionRepository $questionRepo): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = $request->query->getInt('limit', 10);
        $filters = $request->query->all();

        $result = $questionRepo->getPaginatedQuestions($page, $limit, $filters);
        dd($result);
        return $this->json(['result' => $result], 200, [],  ['groups' => 'question-read']);
    }
}
