<?php

namespace App\Tests;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class ControllerTest extends WebTestCase
{   
    public function testListTasks()
{
    $client = static::createClient();

    $client->followRedirects(); // Указание клиенту следовать за перенаправлениями

    $crawler=$client->request('GET', '/task'); 

    // Проверяем, что страница успешно загрузилась
    $this->assertResponseIsSuccessful();

    }

}



    
