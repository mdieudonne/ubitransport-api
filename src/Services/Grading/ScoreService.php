<?php

namespace App\Services\Grading;

use App\Core\ApiError;
use App\Core\ApiErrorException;
use App\Entity\Grading\Score;
use App\Entity\Grading\Student;
use Doctrine\ORM\EntityManagerInterface;

class ScoreService
{
  private EntityManagerInterface $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function add(array $data): Score
  {

    $this->validateRequestParams($data);

    $score = new Score();
    $score->setValue($data['value']);
    $score->setSubject($data['subject']);

    /** @var Student $student */
    $student = $this->em->getRepository(Student::class)->find($data['id_student']);
    if (!$student) {
      $error = new ApiError(404, ApiError::RESOURCE_NOT_FOUND);
      throw new ApiErrorException($error);
    }

    $score->setStudent($student);

    $this->em->persist($score);
    $this->em->flush();

    return $score;
  }

  private function validateRequestParams(array $data): void
  {
    if (empty($data['value'] || empty($data['subject']))) {
      $error = new ApiError(400, ApiError::MISSING_PARAM);
      throw new ApiErrorException($error);
    }

  }

}
