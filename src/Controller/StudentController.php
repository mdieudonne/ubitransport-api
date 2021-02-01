<?php

namespace App\Controller;

use Exception;
use StudentService;
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
   *   name="add_student",
   *   methods={"POST"}
   * )
   *
   * @param Request $request
   * @param StudentService $studentService
   * @return JsonResponse
   */
  public function addStudent(Request $request, StudentService $studentService): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $student = $studentService->add($data);

    return new JsonResponse($student, Response::HTTP_CREATED);
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

    $student = $studentService->update($data, $id);

    return new JsonResponse($student, Response::HTTP_OK);
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
