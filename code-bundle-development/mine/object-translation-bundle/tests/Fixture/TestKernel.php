<?php

namespace AlmtCasts\ObjectTranslationBundle\Tests\Fixture;

use AlmtCasts\ObjectTranslationBundle\ObjectTranslationBundle;
use AlmtCasts\ObjectTranslationBundle\Tests\Fixture\Entity\Translation;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Kernel;
use Zenstruck\Foundry\ZenstruckFoundryBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        yield new FrameworkBundle();
        yield new DoctrineBundle();
        yield new ZenstruckFoundryBundle();
        yield new ObjectTranslationBundle();
    }

    private function configureContainer(
        ContainerConfigurator $container,
        LoaderInterface $loader,
        ContainerBuilder $builder
    ): void {
        $container->extension('framework', [
            'test' => true,
        ]);

        $container->extension('almtcasts_object_translation', [
            'translation_class' => Translation::class,
        ]);

        $container->extension('doctrine', [
            'dbal' => [
                'url' => 'sqlite:///%kernel.project_dir%/var/data.db',
            ],
            'orm' => [
                'mappings' => [
                    'Test' => [
                        'dir' => '%kernel.project_dir%/tests/Fixture/Entity',
                        'prefix' => 'AlmtCasts\ObjectTranslationBundle\Tests\Fixture\Entity',
                    ],
                ],
            ],
        ]);
    }
}
