<?php

namespace AlmtCasts\ObjectTranslationBundle\Tests\Fixture\Entity;

use AlmtCasts\ObjectTranslationBundle\Model\Translation as BaseTranslation;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Translation extends BaseTranslation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    public int $id;
}
