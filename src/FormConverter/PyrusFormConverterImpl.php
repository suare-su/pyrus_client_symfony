<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverter;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Object that can convert Pyrus form to Symfony form.
 *
 * @psalm-api
 */
final class PyrusFormConverterImpl implements PyrusFormConverter
{
    /**
     * @param iterable<PyrusFieldConverter> $fieldsConverters
     */
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
        private readonly iterable $fieldsConverters,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Form $pyrusForm): PyrusFormConverterResult
    {
        $formBuilder = $this->formFactory->createBuilder();

        foreach ($pyrusForm->fields as $field) {
            $this->convertAndBuildField($pyrusForm, $field, $formBuilder);
        }

        return new PyrusFormConverterResult($pyrusForm, $formBuilder->getForm());
    }

    /**
     * Convert single field and add it to builder.
     */
    private function convertAndBuildField(Form $pyrusForm, FormField $field, FormBuilderInterface $builder): void
    {
        foreach ($this->fieldsConverters as $converter) {
            if ($converter->supportsConversion($pyrusForm, $field)) {
                $converter->convert($pyrusForm, $field, $builder);

                return;
            }
        }

        throw new \RuntimeException("Can't convert filed '{$field->name}' in '{$pyrusForm->name}'");
    }
}
