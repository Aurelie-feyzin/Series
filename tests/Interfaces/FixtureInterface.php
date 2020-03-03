<?php declare(strict_types=1);

namespace App\Tests\Interfaces;

interface FixtureInterface
{
    public function setUp(): void;

    public function tearDown(): void;

    public function loadFixture(): void;
}
