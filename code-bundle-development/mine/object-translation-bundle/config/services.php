<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use AlmtCasts\ObjectTranslationBundle\Command\ObjectTranslationExportCommand;
use AlmtCasts\ObjectTranslationBundle\Command\ObjectTranslationWarmupCommand;
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
        ->set('.almtcasts.object_translator.warmup_command', ObjectTranslationWarmupCommand::class)
            ->args([
                service('almtcasts.object_translator'),
                service('.almtcasts.object_translator.mapping_manager'),
                service('translation.locale_switcher'),
                param('kernel.enabled_locales'),
            ])
            ->tag('console.command')
        ->set('.almtcasts.object_translator.export_command', ObjectTranslationExportCommand::class)
            ->args([
                service('.almtcasts.object_translator.mapping_manager'),
            ])
            ->tag('console.command')
        ->set('.almtcasts.object_translator.twig_extension', ObjectTranslatorExtension::class)
            ->tag('twig.extension')
    ;
};
