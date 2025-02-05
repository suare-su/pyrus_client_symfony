<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;

/**
 * Converter for the text field.
 *
 * @psalm-api
 */
final class PyrusFieldConverterEmail implements PyrusFieldConverter
{
    /**
     * {@inheritdoc}
     */
    public function supportsConversion(Form $pyrusForm, FormField $field): bool
    {
        return FormFieldType::EMAIL === $field->type;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Form $pyrusForm, FormField $field, FormBuilderInterface $builder): void
    {
        $htmlName = PyrusFieldConverterHelper::getHtmlName($field);

        $options = PyrusFieldConverterHelper::getDefaultOptions($field);
        $options['constraints'] = (array) ($options['constraints'] ?? []);
        $options['constraints'][] = new Email();

        $builder->add($htmlName, EmailType::class, $options);
    }
}
