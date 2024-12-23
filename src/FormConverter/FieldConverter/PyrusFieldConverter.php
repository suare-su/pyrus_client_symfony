<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Interface for object that can convert Pyrus form field to Symfony form field.
 *
 * @psalm-api
 */
interface PyrusFieldConverter
{
    /**
     * Check that converter supports conversion for provided field.
     */
    public function supportsConversion(Form $pyrusForm, FormField $field): bool;

    /**
     * Convert Pyrus form field to Symfony form field.
     */
    public function convert(Form $pyrusForm, FormField $field, FormBuilderInterface $builder): void;
}
