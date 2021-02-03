<?php

namespace App\Controller\Grading;

use App\Core\ApiError;
use App\Core\ApiErrorException;
use App\Services\Grading\StudentService;
use Exception;
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
   * @return JsonResponse
   */
  public function getStudents(Request $request, StudentService $studentService): JsonResponse
  {
    $page = $request->query->get('page');
    $limit = $request->query->get('itemsPerPage');

    if (!$page || !$limit) {
      $error = new ApiError(400, ApiError::MISSING_PARAM);
      throw new ApiErrorException($error);
    }

    [$students, $totalItems] = $studentService->getByPage($limit, $page);

    return new JsonResponse(
      [
        'students' => $students,
        'totalItems' => $totalItems,
        'page' => $page,
        'itemsPerPage' => $limit,
      ], Response::HTTP_OK
    );
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

    $studentSerialized = $this->get('serializer')->serialize($student, 'json');

    return new Response($studentSerialized, Response::HTTP_CREATED);
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
   * @return JsonResponse
   * @throws Exception
   */
  public function updateStudent(Request $request, StudentService $studentService, int $id): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    if ($data === null) {
      $error = new ApiError(400, ApiError::TYPE_INVALID_REQUEST_BODY_FORMAT);
      throw new ApiErrorException($error);
    }

    $student = $studentService->update($data, $id);
    $studentSerialized = $this->get('serializer')->serialize($student, 'json');

    return new JsonResponse($studentSerialized, Response::HTTP_OK);
  }

  /**
   * @Route(
   *   "/api/students/{id}",
   *   name="delete_student",
   *   methods={"DELETE"}
   * )
   *
   * @param Request $request
   * @param StudentService $studentService
   * @param int $id
   * @return Response
   */
  public function deleteStudent(Request $request, StudentService $studentService, int $id): Response
  {
    $studentService->delete($id);

    $response = new Response();
    $response->setStatusCode(Response::HTTP_NO_CONTENT);

    return $response;
  }
}
