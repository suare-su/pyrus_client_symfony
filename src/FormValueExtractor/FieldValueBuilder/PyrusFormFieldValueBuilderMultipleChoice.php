<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;

/**
 * Convert multiple_choice field value.
 *
 * @psalm-api
 */
final class PyrusFormFieldValueBuilderMultipleChoice implements PyrusFormFieldValueBuilder
{
    /**
     * {@inheritdoc}
     */
    public function supports(FormField $field, mixed $value): bool
    {
        return FormFieldType::MULTIPLE_CHOICE === $field->type;
    }

    /**
     * Convert field and value to FormTaskCreateField.
     */
    public function build(FormField $field, mixed $value): FormTaskCreateField
    {
        return new FormTaskCreateField(
            $field->id,
            [
                'choice_ids' => \is_array($value) ? $value : [$value],
            ]
        );
    }
}
