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
 * \Tests\Feature\Playground\Make\Blade\Console\Commands\BladeMakeCommand\CommandTest
 */
#[CoversClass(BladeMakeCommand::class)]
class CommandTest extends TestCase
{
    public function test_command_without_options_or_arguments(): void
    {
        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan('playground:make:blade');
        $result->assertExitCode(1);
        $result->expectsOutputToContain(__('playground-make::generator.input.error'));
    }

    public function test_command_skeleton(): void
    {
        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan('playground:make:blade testing --skeleton --force');
        $result->assertExitCode(0);
    }
}
