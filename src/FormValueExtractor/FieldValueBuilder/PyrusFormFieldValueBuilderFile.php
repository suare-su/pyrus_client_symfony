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
        if (null === $value) {
            return new FormTaskCreateField($field->id, null);
        }

        $convertedValues = [];
        foreach ($this->getUploadedFileArray($value) as $file) {
            $savedFile = $file->move(
                $this->targetFolder,
                $this->createSafeNameForFile($file)
            );
            $convertedValues[] = $savedFile->getRealPath();
        }

        return new FormTaskCreateField($field->id, $convertedValues);
    }

    /**
     * Converts the given value into an array of uploaded files.
     *
     * @return UploadedFile[]
     */
    private function getUploadedFileArray(mixed $value): array
    {
        if ($value instanceof UploadedFile) {
            $value = [$value];
        }

        if (!\is_array($value)) {
            throw new \InvalidArgumentException('Value must be in array');
        }

        $result = [];
        foreach ($value as $item) {
            if (!($item instanceof UploadedFile)) {
                throw new \InvalidArgumentException('All items must implement ' . UploadedFile::class);
            }
            $result[] = $item;
        }

        return $result;
    }

    /**
     * Generates a safe filename for the given uploaded file.
     */
    private function createSafeNameForFile(UploadedFile $file): string
    {
        $name = $this->makeStringSafe(
            pathinfo($file->getClientOriginalName(), \PATHINFO_FILENAME)
        );

        $extension = $this->makeStringSafe(
            $file->getClientOriginalExtension()
        );

        return "{$name}.{$extension}";
    }

    /**
     * Sanitizes a string to make it safe for use.
     */
    private function makeStringSafe(string $string): string
    {
        $string = preg_replace('/[^\p{L}\p{N}_]+/u', '_', $string);
        $string = mb_strtolower($string);

        return trim($string, '_');
    }
}
