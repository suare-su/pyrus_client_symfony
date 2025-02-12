<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterHelper;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterText;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @internal
 */
final class PyrusFieldConverterTextTest extends BaseCasePyrusForm
{
    /**
     * @test
     *
     * @dataProvider provideSupportsConversion
     */
    public function testSupportsConversion(FormFieldType $type, bool $expected): void
    {
        $field = $this->createPyrusFieldMock(['type' => $type]);
        $form = $this->createPyrusFormMock($field);

        $converter = new PyrusFieldConverterText();
        $res = $converter->supportsConversion($form, $field);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsConversion(): array
    {
        return [
            'text type' => [
                FormFieldType::TEXT,
                true,
            ],
            'other types' => [
                FormFieldType::NUMBER,
                false,
            ],
        ];
    }

    /**
     * @test
     */
    public function testConvert(): void
    {
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::TEXT,
            ]
        );

        $form = $this->createPyrusFormMock($field);

        $builder = $this->createSymfonyFormBuilderMock();
        $builder->expects($this->once())
            ->method('add')
            ->with(
                $this->identicalTo(PyrusFieldConverterHelper::getHtmlName($field)),
                $this->identicalTo(TextType::class),
                $this->identicalTo(PyrusFieldConverterHelper::getDefaultOptions($field)),
            );

        $converter = new PyrusFieldConverterText();
        $converter->convert($form, $field, $builder);
    }
}
