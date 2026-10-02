<?php

namespace AlmtCasts\ObjectTranslationBundle;

use AlmtCasts\ObjectTranslationBundle\Mapping\Translatable;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Translation\LocaleAwareInterface;

final class ObjectTranslator
{
    public function __construct(
        private LocaleAwareInterface $localeAware,
        private string $defaultLocale,
        private string $translationClass,
        private ManagerRegistry $doctrine,
    ) {
    }

    /**
     * @template T of object
     *
     * @param T $object
     *
     * @return T
     */
    public function translate(object $object): object
    {
        $locale = $this->localeAware->getLocale();

        if ($this->defaultLocale === $locale) {
            return $object;
        }

        return new TranslatedObject($object, $this->translationsFor($object, $locale));
    }

    private function translationsFor(object $object, string $locale): array
    {
        $class = new \ReflectionClass($object);
        $type = $class->getAttributes(Translatable::class)[0]?->newInstance()->name ?? null;

        if (!$type) {
            throw new \LogicException(sprintf('Class "%s" is not translatable.', $object::class));
        }

        $translations = $this->doctrine->getRepository($this->translationClass)->findBy([
            'locale' => $locale,
            'objectType' => $type,
            'objectId' => $object->getId(),
        ]);

        $translationValues = [];

        foreach ($translations as $translation) {
            $translationValues[$translation->field] = $translation->value;
        }

        return $translationValues;
    }
}
