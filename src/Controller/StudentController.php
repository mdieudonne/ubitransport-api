<?php

namespace App\Controller;

use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
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
   * @param EntityManagerInterface $em
   * @return JsonResponse
   * @throws \Exception
   */
  public function addStudent(Request $request, EntityManagerInterface $em): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $student = new Student();
    $student->setFirstname($data['firstname']);
    $student->setLastname($data['lastname']);
    $student->setBirthdate(new \DateTime($data['birthdate']));

    $em->persist($student);
    $em->flush();

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
   * @param EntityManagerInterface $em
   * @param int $id
   * @return JsonResponse
   * @throws \Exception
   */
  public function updateStudent(Request $request, EntityManagerInterface $em, int $id): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $student = $em->getRepository(Student::class)->find($id);
    $student->setFirstname($data['firstname']);
    $student->setLastname($data['lastname']);
    $student->setBirthdate(new \DateTime($data['birthdate']));

    $em->flush();
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
   * @param EntityManagerInterface $em
   * @param int $id
   * @return Response
   */
  public function deleteStudent(Request $request, EntityManagerInterface $em, int $id): Response
  {
    $student = $em->getRepository(Student::class)->find($id);
    $em->remove($student);
    $em->flush();

    $response = new Response();
    $response->setStatusCode(Response::HTTP_NO_CONTENT);
    return $response;
  }
}
