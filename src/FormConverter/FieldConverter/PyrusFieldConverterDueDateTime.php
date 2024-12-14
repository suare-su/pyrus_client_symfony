<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Converter for the text field.
 *
 * @psalm-api
 */
final class PyrusFieldConverterDueDateTime implements PyrusFieldConverter
{
    /**
     * {@inheritdoc}
     */
    public function supportsConversion(Form $pyrusForm, FormField $field): bool
    {
        return FormFieldType::DUE_DATE_TIME === $field->type;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Form $pyrusForm, FormField $field, FormBuilderInterface $builder): void
    {
        $options = PyrusFieldConverterHelper::getDefaultOptions($field);
        $options['format'] = "yyyy-MM-dd'T'HH:mm:ss'Z'";

        $builder->add(
            PyrusFieldConverterHelper::getHtmlName($field),
            DateTimeType::class,
            $options
        );
    }
}
