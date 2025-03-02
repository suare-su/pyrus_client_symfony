<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;

/**
 * Convert value and field to FormTaskCreateField.
 *
 * @psalm-api
 */
interface PyrusFormFieldValueBuilder
{
    /**
     * Check that builder supports field.
     */
    public function supports(FormField $field, mixed $value): bool;

    /**
     * Convert field and value to FormTaskCreateField.
     */
    public function build(FormField $field, mixed $value): FormTaskCreateField;
}
