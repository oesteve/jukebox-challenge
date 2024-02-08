<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

abstract class AbstractFunctionalTest extends KernelTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();
    }

    /**
     * @template T of Object
     *
     * @param class-string<T> $class
     *
     * @return T
     */
    protected function getFromContainer(string $class)
    {
        $container = static::getContainer();

        /** @var T */
        $res = $container->get($class);

        return $res;
    }
}
