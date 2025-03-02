<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder\PyrusFormFieldValueBuilderDefault;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;

/**
 * @internal
 */
final class PyrusFormFieldValueBuilderDefaultTest extends BaseCasePyrusForm
{
    /**
     * @test
     */
    public function testSupports(): void
    {
        $value = 'test';
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(['id' => $fieldId]);

        $builder = new PyrusFormFieldValueBuilderDefault();
        $res = $builder->supports($field, $value);

        $this->assertTrue($res);
    }

    /**
     * @test
     */
    public function testBuild(): void
    {
        $value = 'test';
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(['id' => $fieldId]);

        $builder = new PyrusFormFieldValueBuilderDefault();
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame($value, $res->value);
    }
}
