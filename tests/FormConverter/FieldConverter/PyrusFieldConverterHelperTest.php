<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter\FieldConverter;

use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterHelper;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;

/**
 * @internal
 */
final class PyrusFieldConverterHelperTest extends BaseCasePyrusForm
{
    /**
     * @test
     */
    public function testGetHtmlName(): void
    {
        $id = 341;
        $field = $this->createPyrusFieldMock(['id' => $id]);

        $res = PyrusFieldConverterHelper::getHtmlName($field);

        $this->assertSame("field_{$id}", $res);
    }

    /**
     * @test
     */
    public function testGetHtmlLabel(): void
    {
        $name = 'test name for label';
        $field = $this->createPyrusFieldMock(['name' => $name]);

        $res = PyrusFieldConverterHelper::getHtmlLabel($field);

        $this->assertSame($name, $res);
    }

    /**
     * @test
     */
    public function testGetDefaultOptions(): void
    {
        $name = 'test name for label';
        $field = $this->createPyrusFieldMock(['name' => $name]);

        $res = PyrusFieldConverterHelper::getDefaultOptions($field);

        $this->assertSame(
            [
                'label' => $name,
            ],
            $res
        );
    }

    /**
     * @test
     */
    public function testGetDefaultOptionsWithCustomType(): void
    {
        $customType = 'custom_type';
        $name = 'test name for label';
        $field = $this->createPyrusFieldMock(['name' => $name]);

        $res = PyrusFieldConverterHelper::getDefaultOptions($field, $customType);

        $this->assertSame(
            [
                'label' => $name,
                'attr' => [
                    'data-type' => $customType,
                ],
            ],
            $res
        );
    }
}
