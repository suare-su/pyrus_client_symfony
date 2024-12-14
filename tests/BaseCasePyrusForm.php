<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests;

use PHPUnit\Framework\MockObject\MockObject;
use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * Base test case for test with Pyrus forms and fields.
 *
 * @internal
 *
 * @psalm-api
 */
abstract class BaseCasePyrusForm extends BaseCase
{
    /**
     * Create mock for pyrus form field.
     *
     * @psalm-suppress MixedArgument
     * @psalm-suppress MixedArgumentTypeCoercion
     */
    protected function createPyrusFieldMock(array $params = []): FormField
    {
        return new FormField(
            (int) ($params['id'] ?? 123),
            $params['type'] ?? FormFieldType::TEXT,
            (string) ($params['name'] ?? 'test name'),
            'test tooltip',
            (array) ($params['info'] ?? [])
        );
    }

    /**
     * Create mock for pyrus form.
     *
     * @param array<FormField>|FormField $fields
     */
    protected function createPyrusFormMock(array|FormField $fields = []): Form
    {
        $fields = \is_array($fields) ? $fields : [$fields];

        return new Form(
            id: 123,
            name: 'form',
            deletedOrClosed: false,
            fields: $fields
        );
    }

    /**
     * Create mock for Symfony form builder.
     *
     * @return FormBuilderInterface&MockObject
     */
    protected function createSymfonyFormBuilderMock(?FormInterface $form = null): FormBuilderInterface
    {
        $builder = $this->mock(FormBuilderInterface::class);

        if (null !== $form) {
            $builder->expects($this->any())->method('getForm')->willReturn($form);
        }

        return $builder;
    }

    /**
     * Create mock for Symfony form factory.
     *
     * @return FormFactoryInterface&MockObject
     */
    protected function createSymfonyFormFactoryMock(?FormBuilderInterface $builder = null): FormFactoryInterface
    {
        $formFactory = $this->mock(FormFactoryInterface::class);

        if (null !== $builder) {
            $formFactory->expects($this->any())->method('createBuilder')->willReturn($builder);
        }

        return $formFactory;
    }
}
