<?php

namespace AlmtCasts\ObjectTranslationBundle\Twig;

use AlmtCasts\ObjectTranslationBundle\ObjectTranslator;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @internal
 */
final class ObjectTranslatorExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('translate_object', [ObjectTranslator::class, 'translate']),
        ];
    }
}
