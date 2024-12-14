<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterIgnore;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;

/**
 * @internal
 */
final class PyrusFieldConverterIgnoreTest extends BaseCasePyrusForm
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

        $converter = new PyrusFieldConverterIgnore();
        $res = $converter->supportsConversion($form, $field);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsConversion(): array
    {
        return [
            'author type' => [
                FormFieldType::AUTHOR,
                true,
            ],
            'creation date type' => [
                FormFieldType::CREATION_DATE,
                true,
            ],
            'note type' => [
                FormFieldType::NOTE,
                true,
            ],
            'project type' => [
                FormFieldType::PROJECT,
                true,
            ],
            'status type' => [
                FormFieldType::STATUS,
                true,
            ],
            'step type' => [
                FormFieldType::STEP,
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
                'type' => FormFieldType::STATUS,
            ]
        );

        $form = $this->createPyrusFormMock($field);

        $builder = $this->createSymfonyFormBuilderMock();
        $builder->expects($this->never())->method('add');

        $converter = new PyrusFieldConverterIgnore();
        $converter->convert($form, $field, $builder);
    }
}
