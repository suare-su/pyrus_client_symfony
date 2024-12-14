<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter;

use PHPUnit\Framework\MockObject\MockObject;
use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormConverter\PyrusFieldConverter;
use SuareSu\PyrusClientSymfony\FormConverter\PyrusFormConverterImpl;
use SuareSu\PyrusClientSymfony\Tests\BaseCase;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @internal
 */
final class PyrusFormConverterImplTest extends BaseCase
{
    /**
     * @test
     */
    public function testConvert(): void
    {
        $symfonyForm = $this->mock(FormInterface::class);
        $formBuilder = $this->createSymfonyFormBuilderMock($symfonyForm);
        $formFactory = $this->createSymfonyFormFactoryMock($formBuilder);

        $pyrusField = $this->createPyrusFieldMock();
        $pyrusForm = $this->createPyrusFormMock($pyrusField);

        $converter = $this->mock(PyrusFieldConverter::class);
        $converter->expects($this->any())
            ->method('supportsConversion')
            ->willReturnCallback(
                fn (Form $pPyrusForm, FormField $pPyrusField): bool => $pPyrusForm === $pyrusForm
                    && $pPyrusField === $pyrusField
            );
        $converter->expects($this->once())
            ->method('convert')
            ->with(
                $this->identicalTo($pyrusForm),
                $this->identicalTo($pyrusField),
                $this->identicalTo($formBuilder)
            );

        $formConverted = new PyrusFormConverterImpl($formFactory, [$converter]);
        $res = $formConverted->convert($pyrusForm);

        $this->assertSame($symfonyForm, $res->symfonyForm);
        $this->assertSame($pyrusForm, $res->pyrusForm);
    }

    /**
     * @test
     */
    public function testConvertMultipleFieldsConverted(): void
    {
        $symfonyForm = $this->mock(FormInterface::class);
        $formBuilder = $this->createSymfonyFormBuilderMock($symfonyForm);
        $formFactory = $this->createSymfonyFormFactoryMock($formBuilder);

        $pyrusFields = [
            $this->createPyrusFieldMock(),
            $this->createPyrusFieldMock(),
        ];
        $pyrusForm = $this->createPyrusFormMock($pyrusFields);

        $converter = $this->mock(PyrusFieldConverter::class);
        $converter->expects($this->any())
            ->method('supportsConversion')
            ->willReturnCallback(
                fn (Form $pPyrusForm, FormField $pPyrusField): bool => $pPyrusForm === $pyrusForm
                    && \in_array($pPyrusField, $pyrusFields, true)
            );
        $converter->expects($this->exactly(\count($pyrusFields)))
            ->method('convert')
            ->with(
                $this->identicalTo($pyrusForm),
                $this->logicalOr(
                    $this->identicalTo($pyrusFields[0]),
                    $this->identicalTo($pyrusFields[1])
                ),
                $this->identicalTo($formBuilder)
            );

        $formConverted = new PyrusFormConverterImpl($formFactory, [$converter]);

        $formConverted->convert($pyrusForm);
    }

    /**
     * @test
     */
    public function testConvertMultipleConverters(): void
    {
        $symfonyForm = $this->mock(FormInterface::class);
        $formBuilder = $this->createSymfonyFormBuilderMock($symfonyForm);
        $formFactory = $this->createSymfonyFormFactoryMock($formBuilder);

        $pyrusField = $this->createPyrusFieldMock();
        $pyrusForm = $this->createPyrusFormMock($pyrusField);

        $converter = $this->mock(PyrusFieldConverter::class);
        $converter->expects($this->any())->method('supportsConversion')->willReturn(false);
        $converter->expects($this->never())->method('convert');

        $converter1 = $this->mock(PyrusFieldConverter::class);
        $converter1->expects($this->any())->method('supportsConversion')->willReturn(true);
        $converter1->expects($this->exactly(1))->method('convert');

        $converter2 = $this->mock(PyrusFieldConverter::class);
        $converter2->expects($this->any())->method('supportsConversion')->willReturn(true);
        $converter2->expects($this->never())->method('convert');

        $formConverted = new PyrusFormConverterImpl($formFactory, [$converter, $converter1, $converter2]);

        $formConverted->convert($pyrusForm);
    }

    /**
     * @test
     */
    public function testConvertCantConvertFieldException(): void
    {
        $symfonyForm = $this->mock(FormInterface::class);
        $formBuilder = $this->createSymfonyFormBuilderMock($symfonyForm);
        $formFactory = $this->createSymfonyFormFactoryMock($formBuilder);

        $pyrusField = $this->createPyrusFieldMock();
        $pyrusForm = $this->createPyrusFormMock($pyrusField);

        $converter = $this->mock(PyrusFieldConverter::class);
        $converter->expects($this->any())->method('supportsConversion')->willReturn(false);
        $converter->expects($this->never())->method('convert');

        $formConverted = new PyrusFormConverterImpl($formFactory, [$converter]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($pyrusField->name);
        $formConverted->convert($pyrusForm);
    }

    /**
     * Create mock for Symfony form builder.
     *
     * @return FormBuilderInterface&MockObject
     */
    private function createSymfonyFormBuilderMock(FormInterface $form): FormBuilderInterface
    {
        $builder = $this->mock(FormBuilderInterface::class);
        $builder->expects($this->any())->method('getFOrm')->willReturn($form);

        return $builder;
    }

    /**
     * Create mock for Symfony form factory.
     *
     * @return FormFactoryInterface&MockObject
     */
    private function createSymfonyFormFactoryMock(FormBuilderInterface $builder): FormFactoryInterface
    {
        $formFactory = $this->mock(FormFactoryInterface::class);
        $formFactory->expects($this->any())->method('createBuilder')->willReturn($builder);

        return $formFactory;
    }

    /**
     * Create mock for pyrus form.
     *
     * @param array<FormField>|FormField $fields
     */
    private function createPyrusFormMock(array|FormField $fields = []): Form
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
     * Create mock for pyrus form field.
     */
    private function createPyrusFieldMock(): FormField
    {
        return new FormField(
            321,
            FormFieldType::DATE,
            'test_field',
            'test lable'
        );
    }
}
