<?php


namespace App\Tests\Api;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class StudentTest extends WebTestCase
{

  public function testAdd()
  {
    $client = self::createClient();

    $payload = [
      'lastname' => 'Fabien',
      'firstname' => 'Henri',
      'birthdate' => '2020-01-01',
    ];

    $client->request(
      'POST',
      'api/students',
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      json_encode($payload)
    );

    $this->assertJsonResponse($client->getResponse(), 201);
  }

  public function testUpdate()
  {
    $client = self::createClient();

    $payload = [
      'lastname' => 'Joe',
      'firstname' => 'Kevin',
      'birthdate' => '2021-01-01',
    ];

    $client->request(
      'PUT',
      'api/students/70',
      [],
      [],
      ['CONTENT_TYPE' => 'application/json'],
      json_encode($payload)
    );

    $this->assertJsonResponse($client->getResponse());
  }

  public function testDelete()
  {
    $client = self::createClient();

    $client->request('DELETE', 'api/students/1');

    $this->assertEquals(204, $client->getResponse()->getStatusCode());
  }

  protected function assertJsonResponse($response, $statusCode = 200)
  {
    $this->assertEquals(
      $statusCode, $response->getStatusCode(),
      $response->getContent()
    );
    $this->assertTrue(
      $response->headers->contains('Content-Type', 'application/json'),
      $response->headers
    );
  }

}
