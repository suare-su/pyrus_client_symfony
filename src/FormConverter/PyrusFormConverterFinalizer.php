<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\FormConverter;

use SuareSu\PyrusClient\Entity\Form\Form;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Object that can run final actions for form builder before build a form.
 * E.g. add submit button.
 *
 * @psalm-api
 */
interface PyrusFormConverterFinalizer
{
    /**
     * Run final actions for form builder before build a form.
     */
    public function finalize(Form $pyrusForm, FormBuilderInterface $builder): void;
}
