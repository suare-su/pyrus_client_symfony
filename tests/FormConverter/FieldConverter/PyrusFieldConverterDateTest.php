<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterDate;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterHelper;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;
use Symfony\Component\Form\Extension\Core\Type\DateType;

/**
 * @internal
 */
final class PyrusFieldConverterDateTest extends BaseCasePyrusForm
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

        $converter = new PyrusFieldConverterDate();
        $res = $converter->supportsConversion($form, $field);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsConversion(): array
    {
        return [
            'date type' => [
                FormFieldType::DATE,
                true,
            ],
            'due date type' => [
                FormFieldType::DUE_DATE,
                true,
            ],
            'other types' => [
                FormFieldType::TEXT,
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
                'type' => FormFieldType::DATE,
            ]
        );

        $form = $this->createPyrusFormMock($field);

        $options = PyrusFieldConverterHelper::getDefaultOptions($field);
        $options['format'] = 'yyyy-MM-dd';
        $options['html5'] = false;

        $builder = $this->createSymfonyFormBuilderMock();
        $builder->expects($this->once())
            ->method('add')
            ->with(
                $this->identicalTo(PyrusFieldConverterHelper::getHtmlName($field)),
                $this->identicalTo(DateType::class),
                $this->identicalTo($options)
            );

        $converter = new PyrusFieldConverterDate();
        $converter->convert($form, $field, $builder);
    }
}
