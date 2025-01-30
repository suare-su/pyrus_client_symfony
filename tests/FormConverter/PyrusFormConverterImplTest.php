<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverter;
use SuareSu\PyrusClientSymfony\FormConverter\PyrusFormConverterFinalizer;
use SuareSu\PyrusClientSymfony\FormConverter\PyrusFormConverterImpl;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;

/**
 * @internal
 */
final class PyrusFormConverterImplTest extends BaseCasePyrusForm
{
    /**
     * @test
     */
    public function testConvert(): void
    {
        $pyrusField = $this->createPyrusFieldMock();
        $pyrusForm = $this->createPyrusFormMock($pyrusField);

        $symfonyForm = $this->mock(FormInterface::class);
        $formBuilder = $this->createSymfonyFormBuilderMock($symfonyForm);

        $formFactory = $this->mock(FormFactoryInterface::class);
        $formFactory->expects($this->once())
            ->method('createNamedBuilder')
            ->with(
                $this->identicalTo("form_{$pyrusForm->id}"),
                $this->anything(),
                $this->anything(),
                $this->identicalTo(['label' => $pyrusForm->name]),
            )
            ->willReturn($formBuilder);

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

        $formConverted = new PyrusFormConverterImpl($formFactory, [$converter], null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($pyrusField->name);
        $formConverted->convert($pyrusForm);
    }

    /**
     * @test
     */
    public function testConvertCantConvertFieldIgnore(): void
    {
        $symfonyForm = $this->mock(FormInterface::class);

        $formBuilder = $this->createSymfonyFormBuilderMock($symfonyForm);

        $formFactory = $this->createSymfonyFormFactoryMock($formBuilder);

        $pyrusField = $this->createPyrusFieldMock();
        $pyrusForm = $this->createPyrusFormMock($pyrusField);

        $converter = $this->mock(PyrusFieldConverter::class);
        $converter->expects($this->any())->method('supportsConversion')->willReturn(false);
        $converter->expects($this->never())->method('convert');

        $formConverted = new PyrusFormConverterImpl($formFactory, [$converter], null, true);
        $res = $formConverted->convert($pyrusForm);

        $this->assertSame($symfonyForm, $res->symfonyForm);
        $this->assertSame($pyrusForm, $res->pyrusForm);
    }

    /**
     * @test
     */
    public function testConvertFormFinalizer(): void
    {
        $symfonyForm = $this->mock(FormInterface::class);
        $formBuilder = $this->createSymfonyFormBuilderMock($symfonyForm);
        $formFactory = $this->createSymfonyFormFactoryMock($formBuilder);

        $pyrusField = $this->createPyrusFieldMock();
        $pyrusForm = $this->createPyrusFormMock($pyrusField);

        $converter = $this->mock(PyrusFieldConverter::class);
        $converter->expects($this->any())->method('supportsConversion')->willReturn(true);

        $finalizer = $this->mock(PyrusFormConverterFinalizer::class);
        $finalizer->expects($this->once())
            ->method('finalize')
            ->with(
                $this->identicalTo($pyrusForm),
                $this->identicalTo($formBuilder)
            );

        $formConverted = new PyrusFormConverterImpl($formFactory, [$converter], $finalizer);

        $formConverted->convert($pyrusForm);
    }
}
