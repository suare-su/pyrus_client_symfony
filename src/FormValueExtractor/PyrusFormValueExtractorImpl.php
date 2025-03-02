<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormValueExtractor;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterHelper;
use SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder\PyrusFormFieldValueBuilder;
use Symfony\Component\Form\FormInterface;

/**
 * Simple implementation of PyrusFormValueExtractor.
 *
 * @psalm-api
 */
final class PyrusFormValueExtractorImpl implements PyrusFormValueExtractor
{
    /**
     * @param iterable<PyrusFormFieldValueBuilder> $fieldValueBuilders
     */
    public function __construct(private readonly iterable $fieldValueBuilders)
    {
    }

    /**
     * {@inheritdoc}
     *
     * @psalm-suppress MixedAssignment
     */
    public function extract(FormInterface $symfonyForm, Form $pyrusForm): array
    {
        $symfonyFormData = (array) $symfonyForm->getData();

        $results = [];
        foreach ($pyrusForm->fields as $pyrusField) {
            $htmlName = PyrusFieldConverterHelper::getHtmlName($pyrusField);
            $value = $symfonyFormData[$htmlName] ?? null;
            $fieldValue = $this->buildFieldValue($pyrusField, $value);
            if (null !== $fieldValue) {
                $results[] = $fieldValue;
            }
        }

        return $results;
    }

    /**
     * Convert single field and add it to builder.
     */
    private function buildFieldValue(FormField $field, mixed $value): ?FormTaskCreateField
    {
        foreach ($this->fieldValueBuilders as $builder) {
            if ($builder->supports($field, $value)) {
                return $builder->build($field, $value);
            }
        }

        return null;
    }
}
