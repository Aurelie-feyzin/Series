<?php declare(strict_types=1);

namespace App\Tests\Traits;

use Symfony\Component\Validator\ConstraintViolation;

trait AssertHasErrorsTraits
{
    public function assertHasErrors(object $object, int $number = 0): void
    {
        self::bootKernel();
        $errors = self::$container->get('validator')->validate($object);
        $messages = [];
        /** @var ConstraintViolation $error */
        foreach ($errors as $error) {
            $messages[] = $error->getPropertyPath() . ' => ' . $error->getMessage();
        }
        $this->assertCount($number, $errors, implode(', ', $messages));
    }
}
