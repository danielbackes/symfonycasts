<?php

namespace AlmtCasts\ObjectTranslationBundle\Command;

use AlmtCasts\ObjectTranslationBundle\ObjectTranslator;
use AlmtCasts\ObjectTranslationBundle\TranslatableMappingManager;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Translation\LocaleSwitcher;

/**
 * @internal
 */
#[AsCommand(
    name: 'object-translation:warmup',
    description: 'Warms up the object translation cache.',
)]
final class ObjectTranslationWarmupCommand extends Command
{
    public function __construct(
        private ObjectTranslator $translator,
        private TranslatableMappingManager $mappingManager,
        private LocaleSwitcher $localeAware,
        private array $locales,
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Warming up Object Translation Cache');
        $count = 0;

        foreach ($io->progressIterate($this->mappingManager->allTranslatableObjects()) as $object) {
            foreach ($this->locales as $locale) {
                $this->localeAware->runWithLocale($locale, function () use ($object, $locale) {
                    $this->translator->translate($object, $locale, ['force_refresh' => true]);
                });
            }

            ++$count;
        }

        $io->success("Warmed up cache for {$count} translatable objects.");

        return self::SUCCESS;
    }

}
