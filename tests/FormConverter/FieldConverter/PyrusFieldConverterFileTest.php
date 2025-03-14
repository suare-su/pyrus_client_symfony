<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterFile;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterHelper;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;
use Symfony\Component\Form\Extension\Core\Type\FileType;

/**
 * @internal
 */
final class PyrusFieldConverterFileTest extends BaseCasePyrusForm
{
    /**
     * @test
     *
     * @dataProvider provideSupportsConversion
     */
    public function testSupportsConversion(FormFieldType $type, bool $expected): void
    {
        $field = $this->createPyrusFieldMock(['type' => $type]);
        $form = $this->createPyrusFormMock($field);

        $converter = new PyrusFieldConverterFile();
        $res = $converter->supportsConversion($form, $field);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsConversion(): array
    {
        return [
            'file type' => [
                FormFieldType::FILE,
                true,
            ],
            'other types' => [
                FormFieldType::TEXT,
                false,
            ],
        ];
    }

    /**
     * @test
     */
    public function testConvert(): void
    {
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::FILE,
            ]
        );

        $form = $this->createPyrusFormMock($field);
        $options = PyrusFieldConverterHelper::getDefaultOptions($field);
        $options['multiple'] = true;

        $builder = $this->createSymfonyFormBuilderMock();
        $builder->expects($this->once())
            ->method('add')
            ->with(
                $this->identicalTo(PyrusFieldConverterHelper::getHtmlName($field)),
                $this->identicalTo(FileType::class),
                $this->identicalTo($options),
            );

        $converter = new PyrusFieldConverterFile();
        $converter->convert($form, $field, $builder);
    }

    /**
     * @test
     */
    public function testConvertSignature(): void
    {
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::FILE,
                'info' => [
                    'code' => 'Signature',
                ],
            ]
        );

        $form = $this->createPyrusFormMock($field);
        $options = PyrusFieldConverterHelper::getDefaultOptions($field);
        $options['multiple'] = false;

        $builder = $this->createSymfonyFormBuilderMock();
        $builder->expects($this->once())
            ->method('add')
            ->with(
                $this->identicalTo(PyrusFieldConverterHelper::getHtmlName($field)),
                $this->identicalTo(FileType::class),
                $this->identicalTo($options),
            );

        $converter = new PyrusFieldConverterFile();
        $converter->convert($form, $field, $builder);
    }
}
