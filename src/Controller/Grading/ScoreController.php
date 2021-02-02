<?php

namespace App\Controller\Grading;

use App\Core\ApiError;
use App\Core\ApiErrorException;
use App\Services\Grading\StudentService;
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

    if ($data === null) {
      $error = new ApiError(400, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
      throw new ApiErrorException($error);
    }

    $student = $studentService->add($data);

    return new JsonResponse($student, Response::HTTP_CREATED);
  }

}
