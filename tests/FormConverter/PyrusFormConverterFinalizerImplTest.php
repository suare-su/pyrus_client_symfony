<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormConverter;

use SuareSu\PyrusClientSymfony\FormConverter\PyrusFormConverterFinalizerImpl;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * @internal
 */
final class PyrusFormConverterFinalizerImplTest extends BaseCasePyrusForm
{
    /**
     * @test
     */
    public function testFinalize(): void
    {
        $label = 'Submit label test';

        $pyrusForm = $this->createPyrusFormMock();

        $formBuilder = $this->createSymfonyFormBuilderMock();
        $formBuilder->expects($this->once())
            ->method('add')
            ->with(
                $this->identicalTo('save'),
                $this->identicalTo(SubmitType::class),
                $this->identicalTo(['label' => $label])
            );

        $translator = $this->mock(TranslatorInterface::class);
        $translator->expects($this->once())
            ->method('trans')
            ->with(
                $this->identicalTo('submit'),
                $this->anything(),
                $this->identicalTo('pyrus.form.converter')
            )
            ->willReturn($label);

        $finalizer = new PyrusFormConverterFinalizerImpl($translator);
        $finalizer->finalize($pyrusForm, $formBuilder);
    }
}
