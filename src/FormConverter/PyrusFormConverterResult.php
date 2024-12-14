<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use Symfony\Component\Form\FormInterface;

/**
 * DTO that represents form conversion results.
 *
 * @psalm-api
 */
final class PyrusFormConverterResult
{
    public function __construct(
        public readonly Form $pyrusForm,
        public readonly FormInterface $symfonyForm,
    ) {
    }
}
