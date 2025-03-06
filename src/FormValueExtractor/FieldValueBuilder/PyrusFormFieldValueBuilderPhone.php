<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;

/**
 * Convert phone value.
 *
 * @psalm-api
 */
final class PyrusFormFieldValueBuilderPhone implements PyrusFormFieldValueBuilder
{
    /**
     * {@inheritdoc}
     */
    public function supports(FormField $field, mixed $value): bool
    {
        return FormFieldType::PHONE === $field->type;
    }

    /**
     * {@inheritdoc}
     */
    public function build(FormField $field, mixed $value): FormTaskCreateField
    {
        if (preg_match("/^(\d{1})(\d{3})(\d{3})(\d{4,})$/", (string) $value, $matches)) {
            return new FormTaskCreateField(
                $field->id,
                "+{$matches[1]} {$matches[2]} {$matches[3]} {$matches[4]}"
            );
        }

        return new FormTaskCreateField($field->id, $value);
    }
}
