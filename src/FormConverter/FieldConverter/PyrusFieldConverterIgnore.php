<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Converter fthat ignores some fields.
 *
 * @psalm-api
 */
final class PyrusFieldConverterIgnore implements PyrusFieldConverter
{
    /**
     * {@inheritdoc}
     */
    public function supportsConversion(Form $pyrusForm, FormField $field): bool
    {
        return \in_array(
            $field->type,
            [
                FormFieldType::AUTHOR,
                FormFieldType::CREATION_DATE,
                FormFieldType::NOTE,
                FormFieldType::PROJECT,
                FormFieldType::STATUS,
                FormFieldType::STEP,
            ],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Form $pyrusForm, FormField $field, FormBuilderInterface $builder): void
    {
    }
}
