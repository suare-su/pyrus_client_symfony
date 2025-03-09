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
     *
     * @dataProvider provideSupports
     */
    public function testSupports(FormFieldType $type, bool $expected): void
    {
        $path = '/test/';
        $value = null;
        $field = $this->createPyrusFieldMock(
            [
                'type' => $type,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderFile($path);
        $res = $builder->supports($field, $value);

        $this->assertSame($expected, $res);
    }

    public static function provideSupports(): array
    {
        return [
            'supports' => [
                FormFieldType::FILE,
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
        $path = '/test/';
        $fieldId = 321;

        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::FILE,
            ]
        );

        $realPath = '/realpath.txt';
        $savedFile = $this->mock(File::class);
        $savedFile->expects($this->any())->method('getRealPath')->willReturn($realPath);

        $clientOriginalName = ' %тЕст,.PhP)))((';
        $clientOriginalExtension = 'PhP)))((';
        $safeName = 'тест.php';
        $uploadedFile = $this->mock(UploadedFile::class);
        $uploadedFile->expects($this->any())->method('getClientOriginalName')->willReturn($clientOriginalName);
        $uploadedFile->expects($this->any())->method('getClientOriginalExtension')->willReturn($clientOriginalExtension);
        $uploadedFile->expects($this->once())
            ->method('move')
            ->with(
                $this->identicalTo($path),
                $this->identicalTo($safeName),
            )
            ->willReturn($savedFile);

        $realPath1 = '/realpath1.txt';
        $savedFile1 = $this->mock(File::class);
        $savedFile1->expects($this->any())->method('getRealPath')->willReturn($realPath1);

        $clientOriginalName1 = 'TeSt.PhP)))((';
        $clientOriginalExtension1 = 'PhP)))((';
        $safeName1 = 'test.php';
        $uploadedFile1 = $this->mock(UploadedFile::class);
        $uploadedFile1->expects($this->any())->method('getClientOriginalName')->willReturn($clientOriginalName1);
        $uploadedFile1->expects($this->any())->method('getClientOriginalExtension')->willReturn($clientOriginalExtension1);
        $uploadedFile1->expects($this->once())
            ->method('move')
            ->with(
                $this->identicalTo($path),
                $this->identicalTo($safeName1),
            )
            ->willReturn($savedFile1);

        $builder = new PyrusFormFieldValueBuilderFile($path);
        $res = $builder->build($field, [$uploadedFile, $uploadedFile1]);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame([$realPath, $realPath1], $res->value);
    }

    /**
     * @test
     */
    public function testBuildSingleFile(): void
    {
        $path = '/test/';
        $fieldId = 321;

        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::FILE,
            ]
        );

        $realPath = '/realpath.txt';
        $savedFile = $this->mock(File::class);
        $savedFile->expects($this->any())->method('getRealPath')->willReturn($realPath);

        $clientOriginalName = 'test.php';
        $clientOriginalExtension = 'php';
        $safeName = 'test.php';
        $uploadedFile = $this->mock(UploadedFile::class);
        $uploadedFile->expects($this->any())->method('getClientOriginalName')->willReturn($clientOriginalName);
        $uploadedFile->expects($this->any())->method('getClientOriginalExtension')->willReturn($clientOriginalExtension);
        $uploadedFile->expects($this->once())
            ->method('move')
            ->with(
                $this->identicalTo($path),
                $this->identicalTo($safeName),
            )
            ->willReturn($savedFile);

        $builder = new PyrusFormFieldValueBuilderFile($path);
        $res = $builder->build($field, $uploadedFile);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame([$realPath], $res->value);
    }

    /**
     * @test
     */
    public function testBuildNullValue(): void
    {
        $fieldId = 321;
        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::FILE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderFile('/test/');
        $res = $builder->build($field, null);

        $this->assertSame($fieldId, $res->id);
        $this->assertNull($res->value);
    }

    /**
     * @test
     */
    public function testBuildNonArrayValue(): void
    {
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::FILE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderFile('/test/');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Value must be in array');
        $builder->build($field, 'test');
    }

    /**
     * @test
     */
    public function testBuildArrayValueWithBrokenItem(): void
    {
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::FILE,
            ]
        );

        $builder = new PyrusFormFieldValueBuilderFile('/test/');

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('All items must implement ' . UploadedFile::class);
        $builder->build($field, ['test']);
    }
}
