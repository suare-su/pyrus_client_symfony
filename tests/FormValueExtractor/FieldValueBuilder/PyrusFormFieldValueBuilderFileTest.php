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
     * 
     * @dataProvider provideBuild
     */
    public function testBuild(int $fieldId, string $clientOriginalName, string $clientOriginalExtension, string $safeName): void
    {
        $path = '/test/';

        $field = $this->createPyrusFieldMock(
            [
                'id' => $fieldId,
                'type' => FormFieldType::FILE,
            ]
        );

        $realPath = '/realpath.txt';
        $savedFile = $this->mock(File::class);
        $savedFile->expects($this->any())->method('getRealPath')->willReturn($realPath);

        $uploadedFile = $this->mock(UploadedFile::class);
        $uploadedFile->expects($this->any())->method('getClientOriginalName')->willReturn($clientOriginalName);
        $uploadedFile->expects($this->any())->method('getClientOriginalExtension')->willReturn($clientOriginalExtension);
        $uploadedFile->expects($this->once())
            ->method('move')
            ->with(
                $this->identicalTo($path),
                $this->callback(
                    fn (string $n): bool => trim(preg_replace("/_([^\._]+)(\.|$)/", '.', $n), '.') === $safeName
                ),
            )
            ->willReturn($savedFile);

        $builder = new PyrusFormFieldValueBuilderFile($path);
        $res = $builder->build($field, $uploadedFile);

        $this->assertSame($fieldId, $res->id);
        $this->assertSame([$realPath], $res->value);
    }

    public static function provideBuild(): array
    {
        return [
            "dots in name" => [
                123,
                "..te........st.php",
                "..php..",
                "te_st_123_0.php"
            ],
            "digits in name" => [
                123,
                "123test.php",
                "php",
                "123test_123_0.php"
            ],
            "random symbols in name" => [
                123,
                "#^$%^$^%test)(*<><>.php",
                "php",
                "test_123_0.php"
            ],
            "only random symbols in name" => [
                123,
                "#^$%^$^%)(*<><>.php",
                "php",
                "incorrect_name_was_provided_123_0.php"
            ],
            "uppercase in name" => [
                123,
                "TeSt.php",
                "php",
                "test_123_0.php"
            ],
            "utf in name" => [
                123,
                "ТеСТ.php",
                "php",
                "тест_123_0.php"
            ],
            "no extension" => [
                123,
                "makefile",
                "",
                "makefile_123_0"
            ],
        ];
    }

    /**
     * @test
     */
    public function testBuildArray(): void
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
        $safeName = "test_{$fieldId}_0.php";
        $uploadedFile = $this->mock(UploadedFile::class);
        $uploadedFile->expects($this->any())->method('getClientOriginalName')->willReturn($clientOriginalName);
        $uploadedFile->expects($this->any())->method('getClientOriginalExtension')->willReturn($clientOriginalExtension);
        $uploadedFile->expects($this->once())
            ->method('move')
            ->with(
                $this->identicalTo($path),
                $this->callback(
                    fn (string $n): bool => trim(preg_replace("/_([^\._]+)(\.|$)/", '.', $n), '.') === $safeName
                )
            )
            ->willReturn($savedFile);

        $realPath1 = '/realpath1.txt';
        $savedFile1 = $this->mock(File::class);
        $savedFile1->expects($this->any())->method('getRealPath')->willReturn($realPath1);

        $clientOriginalName1 = 'test1.php';
        $clientOriginalExtension1 = 'php';
        $safeName1 = "test1_{$fieldId}_1.php";
        $uploadedFile1 = $this->mock(UploadedFile::class);
        $uploadedFile1->expects($this->any())->method('getClientOriginalName')->willReturn($clientOriginalName1);
        $uploadedFile1->expects($this->any())->method('getClientOriginalExtension')->willReturn($clientOriginalExtension1);
        $uploadedFile1->expects($this->once())
            ->method('move')
            ->with(
                $this->identicalTo($path),
                $this->callback(
                    fn (string $n): bool => trim(preg_replace("/_([^\._]+)(\.|$)/", '.', $n), '.') === $safeName1
                )
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
