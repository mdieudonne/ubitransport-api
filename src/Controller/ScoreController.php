<?php

namespace App\Controller;

use App\Entity\Score;
use App\Entity\Student;
use Doctrine\ORM\EntityManagerInterface;
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
   * @return JsonResponse
   */
  public function addScore(Request $request, EntityManagerInterface $em): JsonResponse
  {
    $data = json_decode($request->getContent(), true);

    $score = new Score();
    $score->setValue($data['value']);
    $score->setSubject($data['subject']);

    /** @var Student $student */
    $student = $em->getRepository(Student::class)->find($data['id_student']);
    $score->setStudent($student);

    $em->persist($score);
    $em->flush();

    return new JsonResponse($student, Response::HTTP_CREATED);
  }

}
