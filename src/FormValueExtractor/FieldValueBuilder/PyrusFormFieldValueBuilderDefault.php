<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;

/**
 * Convert all field types to standard object.
 *
 * @psalm-api
 */
final class PyrusFormFieldValueBuilderDefault implements PyrusFormFieldValueBuilder
{
    /**
     * {@inheritdoc}
     */
    public function supports(FormField $field, mixed $value): bool
    {
        return true;
    }

    /**
     * Convert field and value to FormTaskCreateField.
     */
    public function build(FormField $field, mixed $value): FormTaskCreateField
    {
        return new FormTaskCreateField($field->id, $value);
    }
}
