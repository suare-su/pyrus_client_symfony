<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Converter for the text field.
 *
 * @psalm-api
 */
final class PyrusFieldConverterTextMultipleChoice implements PyrusFieldConverter
{
    /**
     * {@inheritdoc}
     */
    public function supportsConversion(Form $pyrusForm, FormField $field): bool
    {
        return FormFieldType::MULTIPLE_CHOICE === $field->type;
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Form $pyrusForm, FormField $field, FormBuilderInterface $builder): void
    {
        $options = PyrusFieldConverterHelper::getDefaultOptions($field);
        $options['choices'] = $this->extractChoices($field);
        $options['multiple'] = false;

        $builder->add(
            PyrusFieldConverterHelper::getHtmlName($field),
            ChoiceType::class,
            $options
        );
    }

    /**
     * Extract list of all options to select from field description.
     *
     * @psalm-suppress MixedAssignment
     */
    private function extractChoices(FormField $field): array
    {
        $res = [];

        $fieldOptions = (array) ($field->info['options'] ?? []);
        foreach ($fieldOptions as $fieldOption) {
            if (!\is_array($fieldOption) || !isset($fieldOption['choice_id'], $fieldOption['choice_value'])) {
                throw new \RuntimeException("Choice for field {$field->name} has wrong format");
            }
            $value = (int) $fieldOption['choice_id'];
            $label = (string) $fieldOption['choice_value'];
            $res[$label] = $value;
        }

        return $res;
    }
}
