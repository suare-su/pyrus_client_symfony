<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter;

use SuareSu\PyrusClient\Entity\Form\Form;

/**
 * Interface for object that can convert Pyrus form to Symfony form.
 *
 * @psalm-api
 */
interface PyrusFormConverter
{
    /**
     * Convert Pyrus form to Symfony form.
     */
    public function convert(Form $pyrusForm, array $options = []): PyrusFormConverterResult;
}
