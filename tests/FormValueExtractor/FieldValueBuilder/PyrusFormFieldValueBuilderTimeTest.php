<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder\PyrusFormFieldValueBuilderTime;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;

/**
 * @internal
 */
final class PyrusFormFieldValueBuilderTimeTest extends BaseCasePyrusForm
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

        $builder = new PyrusFormFieldValueBuilderTime();
        $res = $builder->supports($field, $value);

        $this->assertSame($expected, $res);
    }

    public static function provideSupports(): array
    {
        return [
            'supports' => [
                FormFieldType::TIME,
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
    public function testBuild(?\DateTimeInterface $value, ?string $expected): void
    {
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::DATE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderTime();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame($expected, $res->value);
    }

    public static function provideBuild(): array
    {
        return [
            'time' => [
                new \DateTimeImmutable('2025-10-10 10:10'),
                '10:10',
            ],
            'null' => [
                null,
                null,
            ],
        ];
    }
}
