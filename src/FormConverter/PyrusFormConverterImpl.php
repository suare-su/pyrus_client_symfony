<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;

/**
 * Object that can convert Pyrus form to Symfony form.
 *
 * @psalm-api
 */
final class PyrusFormConverterImpl implements PyrusFormConverter
{
    public function __construct(
        private readonly FormFactoryInterface $formFactory,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Form $pyrusForm): PyrusFormConverterResult
    {
        $formBuilder = $this->formFactory->createBuilder();

        foreach ($pyrusForm->fields as $field) {
            $this->convertAndBuildField($formBuilder, $field);
        }

        return new PyrusFormConverterResult($pyrusForm, $formBuilder->getForm());
    }

    /**
     * Convert and build single field.
     */
    private function convertAndBuildField(FormBuilderInterface $builder, FormField $field): void
    {
    }
}
