<?php

namespace App\Tests\Service;

use App\Service\Slugger;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    public function testSlugsATrickTitle()
    {
        $slugger = new Slugger();
        $title = ' Titre Testé à l\'envers';
        $slug = $slugger->slugIt($title);

        $this->assertEquals('titre-teste-a-lenvers', $slug);
    }
}
