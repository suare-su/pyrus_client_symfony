<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder\PyrusFormFieldValueBuilderMultipleChoice;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;

/**
 * @internal
 */
final class PyrusFormFieldValueBuilderMultipleChoiceTest extends BaseCasePyrusForm
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

        $builder = new PyrusFormFieldValueBuilderMultipleChoice();
        $res = $builder->supports($field, $value);

        $this->assertSame($expected, $res);
    }

    public static function provideSupports(): array
    {
        return [
            'supports' => [
                FormFieldType::MULTIPLE_CHOICE,
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
        $value = '1';
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::MULTIPLE_CHOICE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderMultipleChoice();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame(['choice_ids' => [$value]], $res->value);
    }

    /**
     * @test
     */
    public function testBuildArrayValue(): void
    {
        $value = ['1', '2'];
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::MULTIPLE_CHOICE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderMultipleChoice();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame(['choice_ids' => $value], $res->value);
    }
}
