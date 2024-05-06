<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Blade\Console\Commands;

use Illuminate\Support\Str;
use Playground\Make\Blade\Configuration\Blade as Configuration;
use Playground\Make\Configuration\Contracts\PrimaryConfiguration as PrimaryConfigurationContract;
use Playground\Make\Console\Commands\GeneratorCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * \Playground\Make\Blade\Console\Commands\BladeMakeCommand
 */
#[AsCommand(name: 'playground:make:blade')]
class BladeMakeCommand extends GeneratorCommand
{
    /**
     * @var class-string<Configuration>
     */
    public const CONF = Configuration::class;

    /**
     * @var PrimaryConfigurationContract&Configuration
     */
    protected PrimaryConfigurationContract $c;

    /**
     * @var array<string, string>
     */
    const SEARCH = [
        'route' => 'welcome',
        // 'base_route' => 'welcome',
        'extends' => 'playground::layouts.site',
        'class' => '',
        'folder' => '',
        'namespace' => '',
        'organization' => '',
        'namespacedModel' => '',
        'NamespacedDummyUserModel' => '',
        'namespacedUserModel' => '',
        'user' => '',
        'model' => '',
        'modelVariable' => '',
        'model_column' => '',
        'model_label' => '',
        'module' => '',
        'module_slug' => '',
        'title' => 'Welcome',
        'sections' => '',
        'package' => '',
        'config' => '',
        'form_info_has_one' => '',
    ];

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'playground:make:blade';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new blade template';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Blade';

    protected string $path_destination_folder = 'resources/views';

    public function prepareOptions(): void
    {
        $options = $this->options();
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$options' => $options,
        //     '$this->configuration' => $this->configuration,
        //     '$this->searches' => $this->searches,
        // ]);

        $type = $this->getConfigurationType();

        if (! empty($options['route']) && is_string($options['route'])) {
            $this->c->setOptions([
                'route' => $options['route'],
            ]);
            $this->searches['route'] = $this->c->route();
        }

        if (! empty($options['title']) && is_string($options['title'])) {
            $this->c->setOptions([
                'title' => $options['title'],
            ]);
            $this->searches['title'] = $this->c->title();
        }

        if (! empty($options['extends']) && is_string($options['extends'])) {
            $this->c->setOptions([
                'extends' => $options['extends'],
            ]);
            $this->searches['extends'] = $this->c->extends();
        }

        if (in_array($type, [
            'playground-resource',
        ])) {
            $this->c->setOptions([
                'folder' => Str::of($this->c->name())->kebab()->toString(),
            ]);
        }
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$type' => $type,
        //     '$this->c->type()' => $this->c->type(),
        //     '$this->c->name()' => $this->c->name(),
        //     '$this->c->folder()' => $this->c->folder(),
        // ]);
    }

    protected function getConfigurationFilename(): string
    {
        return sprintf(
            '%1$s/%2$s.json',
            Str::of($this->c->name())->kebab(),
            Str::of($this->getType())->kebab(),
        );
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     */
    protected function qualifyClass($name): string
    {
        $type = $this->getConfigurationType();

        if (empty($this->configuration['folder'])) {
            $this->c->setOptions([
                'folder' => Str::of($name)->kebab()->toString(),
            ]);
            $this->searches['folder'] = $this->c->folder();
        }

        if (empty($this->configuration['model_column'])) {
            $this->c->setOptions([
                'model_column' => Str::of($name)->snake()->replace('-', '_')->toString(),
                'model_label' => Str::of($name)->title()->toString(),
            ]);
            $this->searches['model_column'] = $this->c->model_column();
            $this->searches['model_label'] = $this->c->model_label();
        }

        if ($type === 'site') {
            $this->c->setOptions([
                'class' => sprintf('%1$s.blade', $this->c->folder()),
            ]);
        } elseif ($type === 'playground') {
            $this->c->setOptions([
                'class' => sprintf('%1$s.blade', $this->c->folder()),
            ]);
        } elseif ($type === 'playground-resource-index') {
            $this->c->setOptions([
                'class' => 'index.blade',
            ]);
        } elseif ($type === 'playground-resource') {
            $this->c->setOptions([
                'class' => sprintf('%1$s/index.blade', $this->c->folder()),
            ]);
        } else {
            $this->c->setOptions([
                'class' => sprintf('%1$s.blade', $this->c->folder()),
            ]);
        }

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$type' => $type,
        //     '$this->configuration' => $this->configuration,
        //     '$this->searches' => $this->searches,
        //     '$this->options()' => $this->options(),
        // ]);
        return $this->c->class();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (parent::handle()) {
            return $this->return_status;
        }

        $type = $this->getConfigurationType();

        if ($type === 'playground-resource') {
            $this->handle_playground_resource();
        }

        $this->saveConfiguration();

        return $this->return_status;
    }

    protected function handle_playground_resource(): void
    {
        /**
         * @var array<string, string> $blades
         */
        $blades = [];

        $blades['detail.blade.php'] = 'blade/playground/resource/model/detail.blade.php.stub';
        $blades['form.blade.php'] = 'blade/playground/resource/model/form.blade.php.stub';
        $blades['form-info.blade.php'] = 'blade/playground/resource/model/form-info.blade.php.stub';
        $blades['form-publishing.blade.php'] = 'blade/playground/resource/model/form-publishing.blade.php.stub';
        $blades['form-status.blade.php'] = 'blade/playground/resource/model/form-status.blade.php.stub';
        // $blades['index'] = 'blade/playground/resource/model/index.blade.php.stub';

        foreach ($blades as $blade => $source) {

            // $path_stub = 'blade'.$blade;
            $path = $this->resolveStubPath($source);

            $destination = sprintf(
                '%1$s/%2$s%3$s',
                $this->folder(),
                $this->c->folder() ? $this->c->folder().'/' : '',
                $blade
            );
            // dd([
            //     '__METHOD__' => __METHOD__,
            //     '$source' => $source,
            //     '$path' => $path,
            //     '$destination' => $destination,
            //     '$this->folder' => $this->folder(),
            //     '$this->c' => $this->c,
            // ]);
            $stub = $this->files->get($path);

            $this->search_and_replace($stub);

            $full_path = $this->laravel->storagePath().$destination;
            $this->files->put($full_path, $stub);

            $this->components->info(sprintf('Blade: %s [%s] created successfully.', $blade, $full_path));
        }
    }

    /**
     * Get the stub file for the generator.
     */
    protected function getStub(): string
    {
        $blade = 'blade/template.blade.php.stub';

        $type = $this->getConfigurationType();

        if ($type === 'site') {
            $blade = 'blade/playground/site.blade.php.stub';
        } elseif ($type === 'playground') {
            $blade = 'blade/playground/site.blade.php.stub';
        } elseif ($type === 'playground-resource-index') {
            $blade = 'blade/playground/resource/index.blade.php.stub';
        } elseif ($type === 'playground-resource') {
            $blade = 'blade/playground/resource/model/index.blade.php.stub';
        }

        return $this->resolveStubPath($blade);
    }

    /**
     * @var array<int, string>
     */
    protected array $options_type_suggested = [
        'site',
        'playground',
        'playground-resource-index',
        'playground-resource',
    ];

    /**
     * Get the console command arguments.
     *
     * @return array<int, mixed>
     */
    protected function getOptions(): array
    {
        $options = parent::getOptions();

        $options[] = ['route', null, InputOption::VALUE_OPTIONAL, 'The base route for breadcrumbs.'];
        $options[] = ['title', null, InputOption::VALUE_OPTIONAL, 'The title of the route for breadcrumbs.'];
        $options[] = ['config', null, InputOption::VALUE_OPTIONAL, 'The config name that will be snake case.'];

        return $options;
    }
}
