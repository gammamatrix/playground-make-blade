<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Tests\Unit\Playground\Make\Blade\Configuration\Blade;

use PHPUnit\Framework\Attributes\CoversClass;
use Tests\Unit\Playground\Make\Blade\TestCase;
use Playground\Make\Blade\Configuration\Blade;

/**
 * \Tests\Unit\Playground\Make\Blade\Configuration\Blade\InstanceTest
 */
#[CoversClass(Blade::class)]
class InstanceTest extends TestCase
{
    public function test_instance(): void
    {
        $instance = new Blade;

        $this->assertInstanceOf(Blade::class, $instance);
    }

    /**
     * @var array<string, mixed>
     */
    protected array $expected_properties = [
        'class' => '',
        'config' => '',
        'fqdn' => '',
        'module' => '',
        'module_slug' => '',
        'name' => '',
        'namespace' => '',
        'organization' => '',
        'package' => '',
        // properties
        'extends' => '',
        'folder' => '',
        'model' => '',
        'model_column' => '',
        'model_label' => '',
        'type' => '',
        'route' => '',
        'title' => '',
        'models' => [],
    ];

    public function test_instance_apply_without_options(): void
    {
        $instance = new Blade;

        $properties = $instance->apply()->properties();

        $this->assertIsArray($properties);

        $this->assertSame($this->expected_properties, $properties);

        $jsonSerialize = $instance->jsonSerialize();

        $this->assertIsArray($jsonSerialize);

        $this->assertSame($properties, $jsonSerialize);
    }

    public function test_folder_is_empty_by_default(): void
    {
        $instance = new Blade;

        $this->assertInstanceOf(Blade::class, $instance);

        $this->assertIsString($instance->folder());
        $this->assertEmpty($instance->folder());
    }

    public function test_blade_for_model_with_file_and_skeleton(): void
    {
        $options = $this->getResourceFileAsArray('blade-model');

        $instance = new Blade($options, true);

        $instance->apply();

        $this->assertEmpty($instance->folder());
        $this->assertTrue($instance->skeleton());

        $this->assertSame('Playground', $instance->organization());
        $this->assertSame('playground-crm', $instance->package());
        $this->assertSame('Crm', $instance->module());
        $this->assertSame('crm', $instance->module_slug());
        $this->assertSame('', $instance->fqdn());
        $this->assertSame('Playground/Crm', $instance->namespace());
        $this->assertSame('Contact', $instance->name());
        $this->assertSame('', $instance->class());
        $this->assertSame('playground-model', $instance->type());
        $this->assertSame([], $instance->uses());
        $this->assertSame([
            'Contact' => 'tmp-testing-model.crm.contact.json',
        ], $instance->models());
    }

    public function test_blade_for_rocket_model_with_file_and_skeleton(): void
    {
        $options = $this->getResourceFileAsArray('blade');
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$options' => $options,
        // ]);

        $instance = new Blade($options, true);

        $instance->apply();
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$options' => $options,
        //     '$instance' => $instance,
        // ]);
        $this->assertNotEmpty($instance->folder());
        $this->assertTrue($instance->skeleton());

        $this->assertSame('Acme', $instance->organization());
        $this->assertSame('acme-demo-resource', $instance->package());
        $this->assertSame('Demo', $instance->module());
        $this->assertSame('demo', $instance->module_slug());
        $this->assertSame('rocket', $instance->model_column());
        $this->assertSame('Rocket', $instance->model_label());
        $this->assertSame('Acme/Demo/Models/Rocket', $instance->model_fqdn());
        $this->assertSame('', $instance->fqdn());
        $this->assertSame('testing', $instance->folder());
        $this->assertSame('Acme/Demo', $instance->namespace());
        $this->assertSame('Rocket', $instance->name());
        $this->assertSame('', $instance->class());
        $this->assertSame('', $instance->type());
        $this->assertSame([], $instance->uses());
        $this->assertSame([], $instance->models());
    }
}
