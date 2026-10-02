<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use AlmtCasts\ObjectTranslationBundle\ObjectTranslator;
use AlmtCasts\ObjectTranslationBundle\TranslatableMappingManager;
use AlmtCasts\ObjectTranslationBundle\Twig\ObjectTranslatorExtension;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('almtcasts.object_translator', ObjectTranslator::class)
            ->args([
                service('translation.locale_switcher'),
                param('kernel.default_locale'),
                service('.almtcasts.object_translator.mapping_manager'),
            ])
            ->tag('twig.runtime')

        ->alias(ObjectTranslator::class, 'almtcasts.object_translator')
        ->set('.almtcasts.object_translator.mapping_manager', TranslatableMappingManager::class)
            ->args([
                abstract_arg('translation class'),
                service('doctrine'),
            ])
        ->set('.almtcasts.object_translator.twig_extension', ObjectTranslatorExtension::class)
            ->tag('twig.extension')
    ;
};
