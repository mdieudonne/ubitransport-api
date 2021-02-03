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

  public function getByPage(string $limit, string $page): array
  {

    $totalStudents = $this->em->getRepository(Student::class)->countAll();

    $offset = 0;
    if (intval($page) > 1) {
      $offset = (intval($page) - 1) * intval ($limit);
    }

    $students = $this->em->getRepository(Student::class)->findByBatch($limit, $offset);

    if (empty($students)) {
      $error = new ApiError(422, ApiError::PAGE_NOT_FOUND);
      throw new ApiErrorException($error);
    }

    return [$students, $totalStudents];
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

}
