<?php

namespace AlmtCasts\ObjectTranslationBundle\Mapping;

#[\Attribute(\Attribute::TARGET_CLASS)]
final class Translatable
{
    public function __construct(
        public string $name,
    ) {
    }
}
