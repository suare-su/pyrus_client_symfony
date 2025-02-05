<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter\FieldConverter;

use SuareSu\PyrusClient\Entity\Form\FormFieldType;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterHelper;
use SuareSu\PyrusClientSymfony\FormConverter\FieldConverter\PyrusFieldConverterPhone;
use SuareSu\PyrusClientSymfony\FormType\PhoneType;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * @internal
 */
final class PyrusFieldConverterPhoneTest extends BaseCasePyrusForm
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

        $converter = new PyrusFieldConverterPhone();
        $res = $converter->supportsConversion($form, $field);

        $this->assertSame($expected, $res);
    }

    public static function provideSupportsConversion(): array
    {
        return [
            'phone type' => [
                FormFieldType::PHONE,
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
                'type' => FormFieldType::PHONE,
            ]
        );

        $form = $this->createPyrusFormMock($field);

        $builder = $this->createSymfonyFormBuilderMock();
        $builder->expects($this->once())
            ->method('add')
            ->with(
                $this->identicalTo(PyrusFieldConverterHelper::getHtmlName($field)),
                $this->identicalTo(PhoneType::class),
                $this->callback(
                    fn (array $o): bool => [] === array_diff_assoc(PyrusFieldConverterHelper::getDefaultOptions($field), $o)
                        && isset($o['constraints'][0])
                        && $o['constraints'][0] instanceof Regex
                )
            );

        $converter = new PyrusFieldConverterPhone();
        $converter->convert($form, $field, $builder);
    }
}
