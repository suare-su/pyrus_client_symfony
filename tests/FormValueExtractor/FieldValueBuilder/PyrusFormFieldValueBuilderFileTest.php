<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormValueExtractor\FieldValueBuilder;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder\PyrusFormFieldValueBuilderFile;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @internal
 */
final class PyrusFormFieldValueBuilderFileTest extends BaseCasePyrusForm
{
    /**
     * @test
     */
    public function testSupports(): void
    {
        $path = '/test/';
        $value = $this->mock(UploadedFile::class);
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::FILE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderFile($path);
        $res = $builder->supports($field, $value);

        $this->assertTrue($res);
    }

    /**
     * @test
     */
    public function testDoesntSupport(): void
    {
        $path = '/test/';
        $value = $this->mock(UploadedFile::class);
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::MULTIPLE_CHOICE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderFile($path);
        $res = $builder->supports($field, $value);

        $this->assertFalse($res);
    }

    /**
     * @test
     */
    public function testBuild(): void
    {
        $path = '/test/';
        $realPath = '/realpath.txt';
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::FILE,
            ]
        );

        $file = $this->mock(File::class);
        $file->expects($this->any())->method('getRealPath')->willReturn($realPath);

        $value = $this->mock(UploadedFile::class);
        $value->expects($this->any())
            ->method('move')
            ->willReturnCallback(
                fn (string $p): File => match ($p) {
                    $path => $file,
                    default => throw new \RuntimeException(),
                }
            );

        $builder = new PyrusFormFieldValueBuilderFile($path);
        $res = $builder->build($field, $value);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame($realPath, $res->value);
    }

    /**
     * @test
     */
    public function testBuildNullValue(): void
    {
        $path = '/test/';
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::FILE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderFile($path);
        $res = $builder->build($field, null);

        $this->assertSame($fieldId, $res->id);
        $this->assertNull($res->value);
    }
}
