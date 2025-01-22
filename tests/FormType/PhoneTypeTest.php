<?php

declare(strict_types=1);

namespace SuareSu\PyrusClientSymfony\Tests\FormType;

use SuareSu\PyrusClientSymfony\FormType\PhoneType;
use SuareSu\PyrusClientSymfony\Tests\BaseCasePyrusForm;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * @internal
 */
final class PhoneTypeTest extends BaseCasePyrusForm
{
    /**
     * @test
     */
    public function testFinalize(): void
    {
        $type = new PhoneType();
        $res = $type->getParent();

        $this->assertSame(TextType::class, $res);
    }
}
