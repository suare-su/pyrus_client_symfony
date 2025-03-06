<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder\PyrusFormFieldValueBuilderCheckMark;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;

/**
 * @internal
 */
final class PyrusFormFieldValueBuilderCheckMarkTest extends BaseCasePyrusForm
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

        $builder = new PyrusFormFieldValueBuilderCheckMark();
        $res = $builder->supports($field, $value);

        $this->assertSame($expected, $res);
    }

    public static function provideSupports(): array
    {
        return [
            'supports' => [
                FormFieldType::CHECKMARK,
                true,
            ],
            "doesn't support" => [
                FormFieldType::TIME,
                false,
            ],
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideBuild
     */
    public function testBuild(mixed $value, ?string $expected): void
    {
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::CHECKMARK,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderCheckMark();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame($expected, $res->value);
    }

    public static function provideBuild(): array
    {
        return [
            'true' => [
                true,
                'checked',
            ],
            'false' => [
                false,
                'unchecked',
            ],
            'null' => [
                null,
                'unchecked',
            ],
            'random string' => [
                'asdasd',
                'unchecked',
            ],
        ];
    }
}
