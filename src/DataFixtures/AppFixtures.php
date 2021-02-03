<?php

namespace App\DataFixtures;

use App\Entity\Grading\Score;
use App\Entity\Grading\Student;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
      $student1 = new Student();
      $student1->setLastname('Casey');
      $student1->setFirstname('Meyer');
      $student1->setBirthdate(new \DateTime('1980-02-03'));
      $manager->persist($student1);

      $score1 = new Score();
      $score1->setSubject('English');
      $score1->setValue(14);
      $score1->setStudent($student1);
      $manager->persist($score1);

      $score2 = new Score();
      $score2->setSubject('Sport');
      $score2->setValue(16);
      $score2->setStudent($student1);
      $manager->persist($score2);


      $student2 = new Student();
      $student2->setLastname('Vince');
      $student2->setFirstname('Chase');
      $student2->setBirthdate(new \DateTime('1982-01-12'));
      $manager->persist($student2);

      $score3 = new Score();
      $score3->setSubject('French');
      $score3->setValue(15.5);
      $score3->setStudent($student2);
      $manager->persist($score3);

      $score4 = new Score();
      $score4->setSubject('History');
      $score4->setValue(12.5);
      $score4->setStudent($student2);
      $manager->persist($score4);

      $manager->flush();
    }
}
