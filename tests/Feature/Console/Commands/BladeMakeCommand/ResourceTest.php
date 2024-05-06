<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Feature\Playground\Make\Blade\Console\Commands\BladeMakeCommand;

use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\CoversClass;
use Playground\Make\Blade\Console\Commands\BladeMakeCommand;
use Tests\Feature\Playground\Make\Blade\TestCase;

/**
 * \Tests\Feature\Playground\Make\Blade\Console\Commands\BladeMakeCommand\ResourceTest
 */
#[CoversClass(BladeMakeCommand::class)]
class ResourceTest extends TestCase
{
    public function test_command_make_playground_resource_blade_with_force_and_without_skeleton(): void
    {
        $command = sprintf(
            'playground:make:blade --force --type playground-resource --file %1$s',
            $this->getResourceFile('blade')
        );
        // dump($command);
        // $result = $this->withoutMockingConsoleOutput()->artisan($command);
        // dd(Artisan::output());

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_resource_blade_with_force_and_with_skeleton(): void
    {
        $command = sprintf(
            'playground:make:blade --skeleton --force --type playground-resource --file %1$s',
            $this->getResourceFile('blade')
        );

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_resource_blade_without_name_with_force_and_without_skeleton_without_file(): void
    {
        $command = 'playground:make:blade --force --type playground-resource';

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(1);
        $result->expectsOutputToContain( __('playground-make::generator.input.error'));
    }

    public function test_command_make_playground_resource_blade_with_force_and_without_skeleton_without_file(): void
    {
        $command = 'playground:make:blade testing --force --type playground-resource';

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_resource_blade_with_force_and_with_skeleton_without_file(): void
    {
        $command = 'playground:make:blade testing --skeleton --force --type playground-resource';

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_resource_index_blade_with_force_and_without_skeleton(): void
    {
        $command = sprintf(
            'playground:make:blade --force --type playground-resource-index --file %1$s',
            $this->getResourceFile('blade')
        );

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_resource_index_blade_with_force_and_with_skeleton(): void
    {
        $command = sprintf(
            'playground:make:blade --skeleton --force --type playground-resource-index --file %1$s',
            $this->getResourceFile('blade')
        );

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_resource_index_blade_with_force_and_without_skeleton_without_file(): void
    {
        $command = 'playground:make:blade testing --force --type playground-resource-index';

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_resource_index_blade_with_force_and_with_skeleton_without_file(): void
    {
        $command = 'playground:make:blade testing --skeleton --force --type playground-resource-index';

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }
}
