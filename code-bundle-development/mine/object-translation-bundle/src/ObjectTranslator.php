<?php

namespace AlmtCasts\ObjectTranslationBundle;

use AlmtCasts\ObjectTranslationBundle\Mapping\Translatable;
use App\Entity\Translation;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Symfony\Contracts\Translation\LocaleAwareInterface;

final class ObjectTranslator
{
    private \WeakMap $translatedObjects;

    public function __construct(
        private LocaleAwareInterface $localeAware,
        private string $defaultLocale,
        private string $translationClass,
        private ManagerRegistry $doctrine,
        private CacheInterface $cache,
    ) {
        $this->translatedObjects = new \WeakMap();
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

        return $this->translatedObjects[$object]
            ??= new TranslatedObject($object, $this->translationsFor($object, $locale));
    }

    private function translationsFor(object $object, string $locale): array
    {
        $class = new \ReflectionClass($object);
        $type = $class->getAttributes(Translatable::class)[0]?->newInstance()->name ?? null;

        if (!$type) {
            throw new \LogicException(sprintf('Class "%s" is not translatable.', $object::class));
        }

        $om = $this->doctrine->getManagerForClass($object::class);

        if (!$om) {
            throw new \LogicException(sprintf('No object manager found for class "%s".', $object::class));
        }

        $id = $om->getClassMetadata($object::class)
            ->getIdentifierValues($object)
        ;

        if (1 !== count($id)) {
            throw new \LogicException(
                sprintf('Class "%s" must have a single identifier to be translatable.', $object::class)
            );
        }

        $id = reset($id);


        return $this->cache->get(
            "object_translation.{$locale}.{$type}.{$id}",
            function(ItemInterface $item) use ($locale, $type, $id) {
                if ($this->cache instanceof TagAwareCacheInterface) {
                    $item->tag(['object-translation', "object-translation-{$type}"]);
                }

                /** @var Translation[] $translations */
                $translations = $this->doctrine->getRepository($this->translationClass)->findBy([
                    'locale' => $locale,
                    'objectType' => $type,
                    'objectId' => $id,
                ]);
                $translationValues = [];
                foreach ($translations as $translation) {
                    $translationValues[$translation->field] = $translation->value;
                }
                return $translationValues;
            }
        );
    }
}
