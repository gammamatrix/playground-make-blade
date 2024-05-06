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
 * \Tests\Feature\Playground\Make\Blade\Console\Commands\BladeMakeCommand\SiteTest
 */
#[CoversClass(BladeMakeCommand::class)]
class SiteTest extends TestCase
{
    public function test_command_make_site_blade_with_force_and_without_skeleton(): void
    {
        $command = sprintf(
            'playground:make:blade --force --type site --file %1$s',
            $this->getResourceFile('blade')
        );

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_site_blade_with_force_and_with_skeleton(): void
    {
        $command = sprintf(
            'playground:make:blade --skeleton --force --type site --file %1$s',
            $this->getResourceFile('blade')
        );

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_site_blade_with_force_and_without_skeleton_without_file(): void
    {
        $command = 'playground:make:blade testing --force --type site';

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_site_blade_with_force_and_with_skeleton_without_file(): void
    {
        $command = 'playground:make:blade testing --skeleton --force --type site';

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_blade_with_force_and_without_skeleton(): void
    {
        $command = sprintf(
            'playground:make:blade --force --type playground --file %1$s',
            $this->getResourceFile('blade')
        );

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_blade_with_force_and_with_skeleton(): void
    {
        $command = sprintf(
            'playground:make:blade --skeleton --force --type playground --file %1$s',
            $this->getResourceFile('blade')
        );

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_blade_with_force_and_without_skeleton_without_file(): void
    {
        $command = 'playground:make:blade testing --force --type playground';

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }

    public function test_command_make_playground_blade_with_force_and_with_skeleton_without_file(): void
    {
        $command = 'playground:make:blade testing --skeleton --force --type playground';

        /**
         * @var \Illuminate\Testing\PendingCommand $result
         */
        $result = $this->artisan($command);
        $result->assertExitCode(0);
    }
}
