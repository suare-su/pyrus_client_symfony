<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder\PyrusFormFieldValueBuilderDate;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;

/**
 * @internal
 */
final class PyrusFormFieldValueBuilderDateTest extends BaseCasePyrusForm
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

        $builder = new PyrusFormFieldValueBuilderDate();
        $res = $builder->supports($field, $value);

        $this->assertSame($expected, $res);
    }

    public static function provideSupports(): array
    {
        return [
            'supports date' => [
                FormFieldType::DATE,
                true,
            ],
            'supports due date' => [
                FormFieldType::DUE_DATE,
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
     */
    public function testBuild(): void
    {
        $value = new \DateTimeImmutable();
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::DATE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderDate();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame($value->format('Y-m-d'), $res->value);
    }

    /**
     * @test
     */
    public function testBuildNull(): void
    {
        $value = null;
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::DATE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderDate();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertNull($res->value);
    }
}
