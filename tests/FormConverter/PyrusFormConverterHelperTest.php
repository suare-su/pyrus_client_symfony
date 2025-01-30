<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter;

use SuareSu\PyrusClientSymfony\FormConverter\PyrusFormConverterHelper;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;

/**
 * @internal
 */
final class PyrusFormConverterHelperTest extends BaseCasePyrusForm
{
    /**
     * @test
     */
    public function testGetHtmlName(): void
    {
        $id = 341;
        $field = $this->createPyrusFormMock([], ['id' => $id]);

        $res = PyrusFormConverterHelper::getHtmlName($field);

        $this->assertSame("form_{$id}", $res);
    }

    /**
     * @test
     */
    public function testGetHtmlLabel(): void
    {
        $name = 'test name for label';
        $field = $this->createPyrusFormMock([], ['name' => $name]);

        $res = PyrusFormConverterHelper::getHtmlLabel($field);

        $this->assertSame($name, $res);
    }

    /**
     * @test
     *
     * @dataProvider provideGetIdFromHtmlName
     */
    public function testGetIdFromHtmlName(?string $name, ?int $expected): void
    {
        $res = PyrusFormConverterHelper::getIdFromHtmlName($name);

        $this->assertSame($expected, $res);
    }

    public static function provideGetIdFromHtmlName(): array
    {
        return [
            'correct name' => [
                'form_123',
                123,
            ],
            'short name' => [
                'form_',
                null,
            ],
            'not int name' => [
                'form_form',
                null,
            ],
            'extra symbols in the beginning' => [
                'test_form_123',
                null,
            ],
            'extra symbols in the ending' => [
                'form_123_test',
                null,
            ],
            'empty string' => [
                '',
                null,
            ],
            'null' => [
                null,
                null,
            ],
        ];
    }

    /**
     * @test
     */
    public function testGetDefaultOptions(): void
    {
        $name = 'test name for label';
        $field = $this->createPyrusFormMock([], ['name' => $name]);

        $res = PyrusFormConverterHelper::getDefaultOptions($field);

        $this->assertSame(
            [
                'label' => $name,
            ],
            $res
        );
    }
}
