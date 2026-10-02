<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use AlmtCasts\ObjectTranslationBundle\ObjectTranslator;
use AlmtCasts\ObjectTranslationBundle\Twig\ObjectTranslatorExtension;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('symfonycasts.object_translator', ObjectTranslator::class)
            ->args([
                service('translation.locale_switcher'),
                param('kernel.default_locale'),
                abstract_arg('translation class'),
                service('doctrine')
            ])
        ->set('.symfonycasts.object_translator.twig_extension', ObjectTranslatorExtension::class)
            ->tag('twig.extension')
        ->alias(ObjectTranslator::class, 'symfonycasts.object_translator')
    ;
};
