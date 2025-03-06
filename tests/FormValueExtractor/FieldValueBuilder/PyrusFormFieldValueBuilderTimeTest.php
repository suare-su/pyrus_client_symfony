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
     */
    public function testSupportsDate(): void
    {
        $value = null;
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::TIME,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderTime();
        $res = $builder->supports($field, $value);

        $this->assertTrue($res);
    }

    /**
     * @test
     */
    public function testDoesntSupport(): void
    {
        $value = null;
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::MULTIPLE_CHOICE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderTime();
        $res = $builder->supports($field, $value);

        $this->assertFalse($res);
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
                'type' => FormFieldType::TIME,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderTime();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame($value->format('H:i'), $res->value);
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
                'type' => FormFieldType::TIME,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderTime();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertNull($res->value);
    }
}
