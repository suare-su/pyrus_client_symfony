<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Object that can run final actions for form builder before build a form.
 * E.g. add submit button.
 *
 * @psalm-api
 */
final class PyrusFormConverterFinalizerImpl implements PyrusFormConverterFinalizer
{
    public function __construct(
        private readonly TranslatorInterface $translator,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function finalize(Form $pyrusForm, FormBuilderInterface $builder): void
    {
        $builder->add(
            'save',
            SubmitType::class,
            [
                'label' => $this->translator->trans('Submit', domain: 'pyrus.form.converter'),
            ]
        );
    }
}
