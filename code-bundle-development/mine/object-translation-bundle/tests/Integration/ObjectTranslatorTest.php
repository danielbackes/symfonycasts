<?php

namespace AlmtCasts\ObjectTranslationBundle\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\Cache\CacheInterface;

class ObjectTranslatorTest extends KernelTestCase
{
    public function testCanAccessService()
    {
        $cache = self::getContainer()->get(CacheInterface::class);
        $this->assertInstanceOf(CacheInterface::class, $cache);
    }
}
