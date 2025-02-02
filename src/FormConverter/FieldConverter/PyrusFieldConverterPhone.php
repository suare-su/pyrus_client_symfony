<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormType\PhoneType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Converter for the text field.
 *
 * @psalm-api
 */
final class PyrusFieldConverterPhone implements PyrusFieldConverter
{
    /**
     * {@inheritdoc}
     */
    public function supportsConversion(Form $pyrusForm, FormField $field): bool
    {
        return FormFieldType::PHONE === $field->type;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Form $pyrusForm, FormField $field, FormBuilderInterface $builder): void
    {
        $builder->add(
            PyrusFieldConverterHelper::getHtmlName($field),
            PhoneType::class,
            PyrusFieldConverterHelper::getDefaultOptions($field)
        );
    }
}
