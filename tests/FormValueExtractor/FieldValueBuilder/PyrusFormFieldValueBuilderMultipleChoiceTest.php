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
     */
    public function testSupports(): void
    {
        $value = '1';
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::MULTIPLE_CHOICE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderMultipleChoice();
        $res = $builder->supports($field, $value);

        $this->assertTrue($res);
    }

    /**
     * @test
     */
    public function testDoesntSupport(): void
    {
        $value = '1';
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::CATALOG,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderMultipleChoice();
        $res = $builder->supports($field, $value);

        $this->assertFalse($res);
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
