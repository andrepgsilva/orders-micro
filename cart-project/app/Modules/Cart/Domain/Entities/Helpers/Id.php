<?php

namespace App\Modules\Cart\Domain\Entities\Helpers;

class Id
{
    public $value;

    public function __construct(string $value = null)
    {
        if ($value === null) {
            $this->value = $this->generateUuid();
            return;
        }

        $this->value = $this->generateIdFromString($value);
    }

    public function getUuid(): string
    {
        return $this->value;
    }

    private function generateUuid(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }

    private function generateIdFromString(string $value): string
    {
        if (! $this->isValidUuid($value)) {
            throw new \Exception('The uuid string is not valid', 1);
        }

        return $value;
    }

    static function isValidUuid(string $value): bool
    {
        return preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i', 
            $value
        );
    }
}
