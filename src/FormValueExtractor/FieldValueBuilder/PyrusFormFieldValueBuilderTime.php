<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;

/**
 * Convert time value.
 *
 * @psalm-api
 */
final class PyrusFormFieldValueBuilderTime implements PyrusFormFieldValueBuilder
{
    /**
     * {@inheritdoc}
     */
    public function supports(FormField $field, mixed $value): bool
    {
        return FormFieldType::TIME === $field->type;
    }

    /**
     * {@inheritdoc}
     */
    public function build(FormField $field, mixed $value): FormTaskCreateField
    {
        if ($value instanceof \DateTimeInterface) {
            return new FormTaskCreateField(
                $field->id,
                $value->format('H:i')
            );
        }

        return new FormTaskCreateField($field->id, null);
    }
}
