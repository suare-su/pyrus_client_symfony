<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterHelper;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterTextMultipleChoice;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * @internal
 */
final class PyrusFieldConverterTextMultipleChoiceTest extends BaseCasePyrusForm
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

        $converter = new PyrusFieldConverterTextMultipleChoice();
        $res = $converter->supportsConversion($form, $field);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsConversion(): array
    {
        return [
            'number type' => [
                FormFieldType::MULTIPLE_CHOICE,
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
                'type' => FormFieldType::MULTIPLE_CHOICE,
                'info' => [
                    'options' => [
                        [
                            'choice_id' => 0,
                            'choice_value' => 'Не выбрано',
                        ],
                        [
                            'choice_id' => 1,
                            'choice_value' => 'Да',
                        ],
                    ],
                ],
            ]
        );

        $options = PyrusFieldConverterHelper::getDefaultOptions($field);
        $options['choices'] = [
            'Не выбрано' => 0,
            'Да' => 1,
        ];
        $options['multiple'] = false;

        $form = $this->createPyrusFormMock($field);

        $builder = $this->createSymfonyFormBuilderMock();
        $builder->expects($this->once())
            ->method('add')
            ->with(
                $this->identicalTo(PyrusFieldConverterHelper::getHtmlName($field)),
                $this->identicalTo(ChoiceType::class),
                $options,
            );

        $converter = new PyrusFieldConverterTextMultipleChoice();
        $converter->convert($form, $field, $builder);
    }

    /**
     * @test
     */
    public function testConvertCHoicesFormatException(): void
    {
        $field = $this->createPyrusFieldMock(
            [
                'type' => FormFieldType::MULTIPLE_CHOICE,
                'info' => [
                    'options' => [
                        [
                            'id' => 0,
                            'name' => 'Не выбрано',
                        ],
                    ],
                ],
            ]
        );
        $form = $this->createPyrusFormMock($field);
        $builder = $this->createSymfonyFormBuilderMock();

        $converter = new PyrusFieldConverterTextMultipleChoice();

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage($field->name);
        $converter->convert($form, $field, $builder);
    }
}
