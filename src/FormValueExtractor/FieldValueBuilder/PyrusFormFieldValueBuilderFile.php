<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Convert file related fields values.
 *
 * @psalm-api
 */
final class PyrusFormFieldValueBuilderFile implements PyrusFormFieldValueBuilder
{
    public function __construct(private readonly string $targetFolder)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function supports(FormField $field, mixed $value): bool
    {
        return FormFieldType::FILE === $field->type;
    }

    /**
     * {@inheritdoc}
     */
    public function build(FormField $field, mixed $value): FormTaskCreateField
    {
        if ($value instanceof UploadedFile) {
            return new FormTaskCreateField(
                $field->id,
                $value->move($this->targetFolder)->getRealPath()
            );
        }

        return new FormTaskCreateField($field->id, null);
    }
}
