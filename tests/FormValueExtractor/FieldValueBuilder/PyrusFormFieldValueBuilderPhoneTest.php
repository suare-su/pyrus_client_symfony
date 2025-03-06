<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder\PyrusFormFieldValueBuilderPhone;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;

/**
 * @internal
 */
final class PyrusFormFieldValueBuilderPhoneTest extends BaseCasePyrusForm
{
    /**
     * @test
     *
     * @dataProvider provideSupports
     */
    public function testSupports(FormFieldType $type, bool $expected): void
    {
        $value = null;
        $field = $this->createPyrusFieldMock(
            [
                'type' => $type,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderPhone();
        $res = $builder->supports($field, $value);

        $this->assertSame($expected, $res);
    }

    public static function provideSupports(): array
    {
        return [
            'supports' => [
                FormFieldType::PHONE,
                true,
            ],
            "doesn't support" => [
                FormFieldType::MULTIPLE_CHOICE,
                false,
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideBuild
     */
    public function testBuild(?string $value, ?string $expected): void
    {
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::PHONE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderPhone();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame($expected, $res->value);
    }

    public static function provideBuild(): array
    {
        return [
            'phone number' => [
                '79998881414',
                '+7 999 888 1414',
            ],
            'longer phone number' => [
                '79998881414999',
                '+7 999 888 1414999',
            ],
            'broken phone number begin' => [
                'a79998881414',
                'a79998881414',
            ],
            'broken phone number end' => [
                '79998881414b',
                '79998881414b',
            ],
            'null' => [
                null,
                null,
            ],
        ];
    }
}
