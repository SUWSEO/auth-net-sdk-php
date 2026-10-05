<?php

namespace net\authorize\util;

use PHPUnit\Framework\TestCase;

class LogTest extends TestCase
{
    public function test_logger_constructs_without_deprecations_and_still_masks_card_numbers(): void
    {
        set_error_handler(function ($severity, $message, $file, $line) {
            throw new \ErrorException($message, 0, $severity, $file, $line);
        }, E_DEPRECATED);

        try {
            $logger = new Log();
            $mask = new \ReflectionMethod(Log::class, 'maskCreditCards');
            $masked = $mask->invoke($logger, 'Test card 4111111111111111');
            $this->assertStringNotContainsString('4111111111111111', $masked);
            $this->assertStringContainsString('xxxx', $masked);
        } finally {
            restore_error_handler();
        }
    }
}
