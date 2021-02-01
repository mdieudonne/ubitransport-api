<?php


use App\Entity\Score;
use App\Entity\Student;
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
    $score = new Score();
    $score->setValue($data['value']);
    $score->setSubject($data['subject']);

    /** @var Student $student */
    $student = $this->em->getRepository(Student::class)->find($data['id_student']);
    $score->setStudent($student);

    $this->em->persist($score);
    $this->em->flush();

    return $score;
  }

}
