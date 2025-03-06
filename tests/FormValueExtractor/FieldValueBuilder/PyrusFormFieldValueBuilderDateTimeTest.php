<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder\PyrusFormFieldValueBuilderDateTime;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;

/**
 * @internal
 */
final class PyrusFormFieldValueBuilderDateTimeTest extends BaseCasePyrusForm
{
    /**
     * @test
     */
    public function testSupportsDate(): void
    {
        $value = null;
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::DUE_DATE_TIME,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderDateTime();
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

        $builder = new PyrusFormFieldValueBuilderDateTime();
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
                'type' => FormFieldType::DUE_DATE_TIME,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderDateTime();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame($value->format('Y-m-d\TH:i:s\Z'), $res->value);
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
                'type' => FormFieldType::DUE_DATE_TIME,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderDateTime();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertNull($res->value);
    }
}
