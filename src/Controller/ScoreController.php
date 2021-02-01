<?php

namespace App\Controller;

use StudentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ScoreController extends AbstractController
{
  /**
   * @Route(
   *   "/api/scores",
   *   name="add_score",
   *   methods={"POST"}
   * )
   *
   * @param Request $request
   * @param StudentService $studentService
   * @return JsonResponse
   */
  public function addScore(Request $request, StudentService $studentService): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $student = $studentService->add($data);

    return new JsonResponse($student, Response::HTTP_CREATED);
  }

}
