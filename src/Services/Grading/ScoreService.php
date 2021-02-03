<?php

namespace App\Services\Grading;

use App\Core\ApiError;
use App\Core\ApiErrorException;
use App\Entity\Grading\Score;
use App\Entity\Grading\Student;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ScoreService
{
  private EntityManagerInterface $em;
  private ValidatorInterface $validator;

  public function __construct(EntityManagerInterface $em, ValidatorInterface $validator)
  {
    $this->em = $em;
    $this->validator = $validator;
  }

  public function getByPage(string $limit, string $page, int $idStudent): array
  {
    $totalItems = $this->em->getRepository(Score::class)->countAllByStudent($idStudent);

    $offset = 0;
    if (intval($page) > 1) {
      $offset = (intval($page) - 1) * intval ($limit);
    }

    $scores = $this->em->getRepository(Score::class)->findByBatchByStudent($limit, $offset, $idStudent);

    if (empty($scores)) {
      $error = new ApiError(422, ApiError::PAGE_NOT_FOUND);
      throw new ApiErrorException($error);
    }

    return [$scores, $totalItems];
  }

  public function add(array $data): Score
  {

    $score = new Score();
    $score->setValue($data['value']);
    $score->setSubject($data['subject']);

    /** @var Student $student */
    $student = $this->em->getRepository(Student::class)->find($data['idStudent']);
    if (!$student) {
      $error = new ApiError(404, ApiError::RESOURCE_NOT_FOUND);
      throw new ApiErrorException($error);
    }

    $score->setStudent($student);
    $student->addScore($score);

    $this->validateScore($score);

    $this->em->persist($score);
    $this->em->flush();

    return $score;
  }


  public function update(array $data, int $id): Score
  {
    /** @var Score $score */
    $score = $this->em->getRepository(Score::class)->find($id);

    if (!$score) {
      $error = new ApiError(404, ApiError::RESOURCE_NOT_FOUND);
      throw new ApiErrorException($error);
    }

    $score->setSubject($data['subject']);
    $score->setValue($data['value']);

    $this->validateScore($score);

    $this->em->flush();

    return $score;
  }

  public function delete(int $id): void
  {
    $score = $this->em->getRepository(Score::class)->find($id);
    if (!$score) {
      $error = new ApiError(404, ApiError::RESOURCE_NOT_FOUND);
      throw new ApiErrorException($error);
    }

    $this->em->remove($score);
    $this->em->flush();
  }

  public function validateScore(Score $score): void
  {
    $errors = $this->validator->validate($score);

    if (count($errors) > 0) {
      $errorsString = (string) $errors;
      $error = new ApiError(400, $errorsString);
      throw new ApiErrorException($error);
    }
  }

  public function calculateAverageScoreByStudent(?int $idStudent = 0): float
  {
    $limit = 50;
    $offset = 0;
    $totalItems = $this->em->getRepository(Score::class)->countAllByStudent($idStudent);

    $sum = 0;
    while ($offset < $totalItems) {
      $scores = $this->em->getRepository(Score::class)->findByBatchByStudent($limit, $offset, $idStudent);

      /** @var Score $score */
      foreach ($scores as $score) {
        $sum += $score['value'];
      }

      $offset += $limit;
    }

    $result = 0;
    if ($totalItems > 0) {
      $result = round($sum / $totalItems , 2);
    }

    return $result;
  }

}
