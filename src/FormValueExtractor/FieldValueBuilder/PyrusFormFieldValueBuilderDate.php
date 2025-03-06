<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;

/**
 * Convert checkmarks value.
 *
 * @psalm-api
 */
final class PyrusFormFieldValueBuilderDate implements PyrusFormFieldValueBuilder
{
    /**
     * {@inheritdoc}
     */
    public function supports(FormField $field, mixed $value): bool
    {
        return FormFieldType::DATE === $field->type
            || FormFieldType::DUE_DATE === $field->type;
    }

    /**
     * {@inheritdoc}
     */
    public function build(FormField $field, mixed $value): FormTaskCreateField
    {
        if ($value instanceof \DateTimeInterface) {
            $stringValue = $value->format('Y-m-d');

            return new FormTaskCreateField($field->id, $stringValue);
        }

        return new FormTaskCreateField($field->id, null);
    }
}
