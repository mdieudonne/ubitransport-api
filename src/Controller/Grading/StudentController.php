<?php

namespace App\Controller\Grading;

use App\Core\ApiError;
use App\Core\ApiErrorException;
use App\Services\Grading\ScoreService;
use App\Services\Grading\StudentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class StudentController extends AbstractController
{
  /**
   * @Route(
   *   "/api/students",
   *   name="get_students",
   *   methods={"GET"}
   * )
   *
   * @param Request $request
   * @param StudentService $studentService
   * @return Response
   */
  public function getStudents(Request $request, StudentService $studentService): Response
  {
    $page = $request->query->get('page');
    $limit = $request->query->get('itemsPerPage');

    if (!$page || !$limit) {
      $error = new ApiError(400, ApiError::MISSING_PARAM);
      throw new ApiErrorException($error);
    }

    [$students, $totalItems] = $studentService->getByPage($limit, $page);

    $results = [
      'students' => $students,
      'totalItems' => $totalItems,
      'page' => $page,
      'itemsPerPage' => $limit,
    ];

    $resultsSerialized = $this->get('serializer')->serialize($results, 'json', ['groups' => 'student']);

    $response = new Response($resultsSerialized, Response::HTTP_OK);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

  /**
   * @Route(
   *   "/api/students",
   *   name="add_student",
   *   methods={"POST"}
   * )
   *
   * @param Request $request
   * @param StudentService $studentService
   * @return Response
   */
  public function addStudent(Request $request, StudentService $studentService): Response
  {
    $data = json_decode($request->getContent(), true);

    if ($data === null) {
      $error = new ApiError(400, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
      throw new ApiErrorException($error);
    }

    $student = $studentService->add($data);

    $studentSerialized = $this->get('serializer')->serialize($student, 'json', ['groups' => 'student']);

    $response = new Response($studentSerialized, Response::HTTP_CREATED);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

  /**
   * @Route(
   *   "/api/students/{id}",
   *   name="update_student",
   *   methods={"PUT"}
   * )
   *
   * @param Request $request
   * @param StudentService $studentService
   * @param int $id
   * @return Response
   */
  public function updateStudent(Request $request, StudentService $studentService, int $id): Response
  {
    $data = json_decode($request->getContent(), true);

    if ($data === null) {
      $error = new ApiError(400, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
      throw new ApiErrorException($error);
    }

    $student = $studentService->update($data, $id);
    $studentSerialized = $this->get('serializer')->serialize($student, 'json', ['groups' => 'student']);

    $response = new Response($studentSerialized, Response::HTTP_OK);
    $response->headers->set('Content-Type', 'application/json');
    return $response;
  }

  /**
   * @Route(
   *   "/api/students/{id}",
   *   name="delete_student",
   *   methods={"DELETE"}
   * )
   *
   * @param StudentService $studentService
   * @param int $id
   * @return Response
   */
  public function deleteStudent(StudentService $studentService, int $id): Response
  {
    $studentService->delete($id);
    return new Response('',Response::HTTP_NO_CONTENT);
  }

  /**
   * @Route(
   *   "/api/student/{id}/getAverage",
   *   name="get_student_average_score",
   *   methods={"GET"}
   * )
   *
   * @param ScoreService $scoreService
   * @param int $id
   * @return Response
   */
  public function getStudentAverageScore(ScoreService $scoreService, int $id): Response
  {
    $result = $scoreService->calculateAverageScoreByStudent($id);
    return new JsonResponse($result,Response::HTTP_OK);
  }

}
