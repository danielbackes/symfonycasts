<?php

namespace AlmtCasts\ObjectTranslationBundle;

/**
 * @template T of object
 *
 * @mixin T
 */
final class TranslatedObject
{
    /**
     * @param T $_inner
     */
    public function __construct(
        private object $_inner,
    ) {
    }

    public function __call(string $name, array $arguments): mixed
    {
        $method = $name;

        if (!method_exists($this->_inner, $method)) {
            $method = 'get'.ucfirst($name);
        }

        return $this->_inner->$method(...$arguments);
    }

    public function __get(string $name): mixed
    {
        return $this->_inner->$name;
    }

    public function __isset(string $name): bool
    {
        return isset($this->_inner->$name);
    }
}
