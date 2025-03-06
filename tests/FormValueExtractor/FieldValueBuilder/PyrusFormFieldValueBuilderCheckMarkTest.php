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
     */
    public function testSupports(): void
    {
        $value = true;
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::CHECKMARK,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderCheckMark();
        $res = $builder->supports($field, $value);

        $this->assertTrue($res);
    }

    /**
     * @test
     */
    public function testDoesntSupport(): void
    {
        $value = true;
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::MULTIPLE_CHOICE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderCheckMark();
        $res = $builder->supports($field, $value);

        $this->assertFalse($res);
    }

    /**
     * @test
     */
    public function testBuildTrue(): void
    {
        $value = true;
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
        $this->assertSame('checked', $res->value);
    }

    /**
     * @test
     */
    public function testBuildFalse(): void
    {
        $value = false;
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
        $this->assertSame('unchecked', $res->value);
    }
}
