<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormType\PhoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Converter for the text field.
 *
 * @psalm-api
 */
final class PyrusFieldConverterPhone implements PyrusFieldConverter
{
    private const PHONE_REGEXP = '/^[0-9]{11}$/';

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
        $htmlName = PyrusFieldConverterHelper::getHtmlName($field);

        $options = PyrusFieldConverterHelper::getDefaultOptions($field);
        $options['constraints'] = (array) ($options['constraints'] ?? []);
        $options['constraints'][] = new Regex(self::PHONE_REGEXP);

        $builder->add($htmlName, PhoneType::class, $options);
    }
}
