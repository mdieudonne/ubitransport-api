<?php

namespace App\Services\Grading;

use App\Core\ApiError;
use App\Core\ApiErrorException;
use App\Entity\Grading\Student;
use Doctrine\ORM\EntityManagerInterface;

class StudentService
{
  private EntityManagerInterface $em;

  public function __construct(EntityManagerInterface $em)
  {
    $this->em = $em;
  }

  public function add(array $data): Student
  {
    $student = new Student();

    [$birthdate] = $this->validateRequestParams($data);

    $student->setFirstname($data['firstname']);
    $student->setLastname($data['lastname']);
    $student->setBirthdate($birthdate);
    $this->em->persist($student);
    $this->em->flush();

    return $student;
  }

  public function update(array $data, int $id): Student
  {
    /** @var Student $student */
    $student = $this->em->getRepository(Student::class)->find($id);

    if (!$student) {
      $error = new ApiError(404, ApiError::RESOURCE_NOT_FOUND);
      throw new ApiErrorException($error);
    }

    [$birthdate] = $this->validateRequestParams($data);

    $student->setFirstname($data['firstname']);
    $student->setLastname($data['lastname']);
    $student->setBirthdate($birthdate);
    $this->em->flush();

    return $student;
  }

  public function delete(int $id): void
  {
    $student = $this->em->getRepository(Student::class)->find($id);
    if (!$student) {
      $error = new ApiError(404, ApiError::RESOURCE_NOT_FOUND);
      throw new ApiErrorException($error);
    }

    $this->em->remove($student);
    $this->em->flush();
  }

  private function validateRequestParams(array $data): ?array
  {
    if (empty($data['firstname'] || empty($data['lastname']) || empty($data['birthdate']))) {
      $error = new ApiError(400, ApiError::MISSING_PARAM);
      throw new ApiErrorException($error);
    }

    $birthdate = new \DateTime($data['birthdate']);
    if (!$birthdate) {
      $error = new ApiError(400, ApiError::INVALID_DATETIME);
      throw new ApiErrorException($error);
    }

    return [$birthdate];
  }

}
