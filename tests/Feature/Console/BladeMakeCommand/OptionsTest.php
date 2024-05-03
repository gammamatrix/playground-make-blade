<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Feature\Playground\Make\Blade\Console\Commands\BladeMakeCommand;

use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Make\Blade\Console\Commands\BladeMakeCommand;
use Tests\Feature\Playground\Make\Blade\TestCase;

/**
 * \Tests\Feature\Playground\Make\Blade\Console\Commands\BladeMakeCommand\OptionsTest
 */
#[CoversClass(BladeMakeCommand::class)]
class OptionsTest extends TestCase
{
    public function test_command_with_options(): void
    {
        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan('playground:make:blade testing --namespace Acme/Testing --package acme-testing --route example --skeleton --force --title Demo --config acme-testing --extends playground::layouts.site --type site --module Acme --model Rocket');
        $result->assertExitCode(0);
    }
}
