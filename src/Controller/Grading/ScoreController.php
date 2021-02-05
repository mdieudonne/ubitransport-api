<?php

namespace App\Controller\Grading;

use App\Entity\Grading\Score;
use App\Core\ApiError;
use App\Core\ApiErrorException;
use App\Services\Grading\ScoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Model;

class ScoreController extends AbstractController
{

  /**
   * @Route(
   *   "/api/scores",
   *   name="get_scores",
   *   methods={"GET"}
   * )
   *
   * @OA\Response(
   *     response=200,
   *     description="Returns the array of Scores of the Student",
   *     @OA\JsonContent(
   *        type="array",
   *        @OA\Items(ref=@Model(type=Score::class, groups={"score"}))
   *     )
   * )
   * @OA\Parameter(
   *     name="page",
   *     in="query",
   *     description="Page",
   *     @OA\Schema(type="string")
   * )
   * @OA\Parameter(
   *     name="itemsPerPage",
   *     in="query",
   *     description="Items per page",
   *     @OA\Schema(type="string")
   * )
   * @OA\Parameter(
   *     name="idStudent",
   *     in="query",
   *     description="Id of the Student",
   *     @OA\Schema(type="integer")
   * )
   * @OA\Tag(name="scores")
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
   * @OA\Response(
   *     response=201,
   *     description="Return the created Score",
   *     @Model(type=Score::class, groups={"score"})
   * )
   * @OA\Parameter(
   *     name="subject",
   *     in="query",
   *     description="Subject",
   *     @OA\Schema(type="string")
   * )
   * @OA\Parameter(
   *     name="value",
   *     in="query",
   *     description="Score",
   *     @OA\Schema(type="float")
   * )
   * @OA\Tag(name="scores")
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
   * @OA\Response(
   *     response=200,
   *     description="Return the updated Score",
   *     @Model(type=Score::class, groups={"score"})
   * )
   * @OA\Parameter(
   *     name="id",
   *     in="path",
   *     description="Id of the Score to update",
   *     @OA\Schema(type="integer")
   * )
   * @OA\Parameter(
   *     name="subject",
   *     in="query",
   *     description="Subject",
   *     @OA\Schema(type="string")
   * )
   * @OA\Parameter(
   *     name="value",
   *     in="query",
   *     description="Score",
   *     @OA\Schema(type="float")
   * )
   * @OA\Tag(name="scores")
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
   * @OA\Response(
   *     response=204,
   *     description="",
   * )
   * @OA\Parameter(
   *     name="id",
   *     in="path",
   *     description="Id of the Score to delete",
   *     @OA\Schema(type="integer")
   * )
   * @OA\Tag(name="scores")
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
   * @OA\Response(
   *     response=200,
   *     description="Returns the average score",
   * )
   * @OA\Tag(name="scores")
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
