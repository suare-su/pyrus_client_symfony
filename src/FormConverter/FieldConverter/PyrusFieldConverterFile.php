<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Converter for the text field.
 *
 * @psalm-api
 */
final class PyrusFieldConverterFile implements PyrusFieldConverter
{
    /**
     * {@inheritdoc}
     */
    public function supportsConversion(Form $pyrusForm, FormField $field): bool
    {
        return FormFieldType::FILE === $field->type;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Form $pyrusForm, FormField $field, FormBuilderInterface $builder): void
    {
        $options = PyrusFieldConverterHelper::getDefaultOptions($field);
        $options['multiple'] = !isset($field->info['code']) || 'Signature' !== $field->info['code'];

        $builder->add(
            PyrusFieldConverterHelper::getHtmlName($field),
            FileType::class,
            $options
        );
    }
}
