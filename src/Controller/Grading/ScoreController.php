<?php

namespace App\Controller\Grading;

use App\Core\ApiError;
use App\Core\ApiErrorException;
use App\Services\Grading\ScoreService;
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
   *   name="get_scores",
   *   methods={"GET"}
   * )
   *
   * @param Request $request
   * @param ScoreService $scoreService
   * @return Response
   */
  public function getScores(Request $request, ScoreService $scoreService): Response
  {
    $page = $request->query->get('page');
    $limit = $request->query->get('itemsPerPage');
    $idStudent = $request->query->get('idStudent');

    if (!$page || !$limit) {
      $error = new ApiError(400, ApiError::MISSING_PARAM);
      throw new ApiErrorException($error);
    }

    [$scores, $totalItems] = $scoreService->getByPage($limit, $page, intval($idStudent));

    $results = [
      'scores' => $scores,
      'totalItems' => $totalItems,
      'page' => $page,
      'itemsPerPage' => $limit,
    ];

    $resultsSerialized = $this->get('serializer')->serialize($results, 'json', ['groups' => 'score']);

    $response = new Response($resultsSerialized, Response::HTTP_OK);
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }

  /**
   * @Route(
   *   "/api/scores",
   *   name="add_score",
   *   methods={"POST"}
   * )
   *
   * @param Request $request
   * @param ScoreService $scoreService
   * @return Response
   */
  public function addScore(Request $request, ScoreService $scoreService): Response
  {
    $data = json_decode($request->getContent(), true);

    if ($data === null) {
      $error = new ApiError(400, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
      throw new ApiErrorException($error);
    }

    $score = $scoreService->add($data);
    $scoreSerialized = $this->get('serializer')->serialize($score, 'json', ['groups' => 'score']);

    $response = new Response($scoreSerialized, Response::HTTP_CREATED);
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }

  /**
   * @Route(
   *   "/api/scores/{id}",
   *   name="update_score",
   *   methods={"PUT"}
   * )
   *
   * @param Request $request
   * @param ScoreService $scoreService
   * @param int $id
   * @return Response
   */
  public function updateScore(Request $request, ScoreService $scoreService, int $id): Response
  {
    $data = json_decode($request->getContent(), true);

    if ($data === null) {
      $error = new ApiError(400, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
      throw new ApiErrorException($error);
    }

    $score = $scoreService->update($data, $id);
    $scoreSerialized = $this->get('serializer')->serialize($score, 'json', ['groups' => 'score']);

    $response = new Response($scoreSerialized, Response::HTTP_OK);
    $response->headers->set('Content-Type', 'application/json');

    return $response;
  }


  /**
   * @Route(
   *   "/api/scores/{id}",
   *   name="delete_score",
   *   methods={"DELETE"}
   * )
   *
   * @param ScoreService $scoreService
   * @param int $id
   * @return Response
   */
  public function deleteScore(ScoreService $scoreService, int $id): Response
  {
    $scoreService->delete($id);

    return new Response('', Response::HTTP_NO_CONTENT);
  }

  /**
   * @Route(
   *   "/api/scores/getAverage",
   *   name="get_average_score",
   *   methods={"GET"}
   * )
   *
   * @param ScoreService $scoreService
   * @return JsonResponse
   */
  public function getAverageScore(ScoreService $scoreService): JsonResponse
  {
    $result = $scoreService->calculateAverageScoreByStudent();

    return new JsonResponse($result, Response::HTTP_OK);
  }

}
