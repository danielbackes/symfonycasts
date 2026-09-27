<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use AlmtCasts\ObjectTranslationBundle\ObjectTranslator;

return static function (ContainerConfigurator $container) {
    $container->services()
        ->set('symfonycasts.object_translator', ObjectTranslator::class)
            ->args([
                service('translation.locale_switcher'),
                param('kernel.default_locale'),
                abstract_arg('translation class')
            ])
        ->alias(ObjectTranslator::class, 'symfonycasts.object_translator')
    ;
};
