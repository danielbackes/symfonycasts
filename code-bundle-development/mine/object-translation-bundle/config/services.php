<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use AlmtCasts\ObjectTranslationBundle\ObjectTranslator;
use AlmtCasts\ObjectTranslationBundle\Twig\ObjectTranslatorExtension;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('almtcasts.object_translator', ObjectTranslator::class)
            ->args([
                service('translation.locale_switcher'),
                param('kernel.default_locale'),
                abstract_arg('translation class'),
                service('doctrine'),
                service('cache.app')
            ])
            ->tag('twig.runtime')
        ->set('.almtcasts.object_translator.twig_extension', ObjectTranslatorExtension::class)
            ->tag('twig.extension')
        ->alias(ObjectTranslator::class, 'almtcasts.object_translator')
    ;
};
