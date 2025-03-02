<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormValueExtractor;

use SuareSu\PyrusClient\Entity\Form\FormField;
use SuareSu\PyrusClient\Entity\Task\FormTaskCreateField;
use SuareSu\PyrusClientSymfony\FormValueExtractor\FieldValueBuilder\PyrusFormFieldValueBuilder;
use SuareSu\PyrusClientSymfony\FormValueExtractor\PyrusFormValueExtractorImpl;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;
use Symfony\Component\Form\FormInterface;

/**
 * @internal
 */
final class PyrusFormValueExtractorImplTest extends BaseCasePyrusForm
{
    /**
     * @test
     */
    public function testExtract(): void
    {
        $data = [
            'field_1' => 'test 1',
            'field_2' => 'test 2',
            'field_3' => 'test 3',
        ];

        $symfonyForm = $this->mock(FormInterface::class);
        $symfonyForm->expects($this->any())->method('getData')->willReturn($data);

        $field1 = $this->createPyrusFieldMock(['id' => 1]);
        $field2 = $this->createPyrusFieldMock(['id' => 2]);
        $field3 = $this->createPyrusFieldMock(['id' => 3]);
        $pyrusForm = $this->createPyrusFormMock([$field1, $field2, $field3]);

        $valueBuilder1 = $this->mock(PyrusFormFieldValueBuilder::class);
        $valueBuilder1->expects($this->any())->method('supports')->willReturn(false);

        $valueBuilder2 = $this->mock(PyrusFormFieldValueBuilder::class);
        $valueBuilder2->expects($this->any())->method('supports')
            ->willReturnCallback(
                fn (FormField $f): bool => $f === $field2 || $f === $field3
            );
        $valueBuilder2->expects($this->any())->method('build')
            ->willReturnCallback(
                fn (FormField $f, mixed $v): FormTaskCreateField => new FormTaskCreateField($f->id, "converted_{$v}")
            );

        $extractor = new PyrusFormValueExtractorImpl([$valueBuilder1, $valueBuilder2]);
        $res = $extractor->extract($symfonyForm, $pyrusForm);

        $this->assertCount(2, $res);
        $this->assertSame($field2->id, $res[0]->id);
        $this->assertSame("converted_{$data['field_2']}", $res[0]->value);
        $this->assertSame($field3->id, $res[1]->id);
        $this->assertSame("converted_{$data['field_3']}", $res[1]->value);
    }
}
