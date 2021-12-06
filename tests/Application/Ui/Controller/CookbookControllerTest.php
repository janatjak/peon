<?php
declare(strict_types=1);

namespace PHPMate\Tests\Application\Ui\Controller;

use PHPMate\Infrastructure\Cookbook\StaticRecipesCollection;
use PHPMate\Tests\DataFixtures\DataFixtures;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class CookbookControllerTest extends WebTestCase
{
    public function testPageCanBeRendered(): void
    {
        $client = self::createClient();

        $projectId = DataFixtures::PROJECT_ID;
        $crawler = $client->request('GET', "/projects/$projectId/cookbook");

        self::assertResponseIsSuccessful();

        $recipesInCollectionCount = count((new StaticRecipesCollection())->all());
        self::assertCount($recipesInCollectionCount, $crawler->filter('.dashboard-projects .col'));
    }
}
