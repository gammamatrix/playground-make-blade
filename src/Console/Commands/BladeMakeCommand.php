<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Blade\Console\Commands;

use Illuminate\Support\Str;
use Playground\Make\Blade\Building;
use Playground\Make\Blade\Configuration\Blade as Configuration;
use Playground\Make\Configuration\Contracts\PrimaryConfiguration as PrimaryConfigurationContract;
use Playground\Make\Console\Commands\GeneratorCommand;
use Playground\Make\Model\Recipe\Model as ModelRecipe;
use Playground\Make\Package\Configuration\Package;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

/**
 * \Playground\Make\Blade\Console\Commands\BladeMakeCommand
 */
#[AsCommand(name: 'playground:make:blade')]
class BladeMakeCommand extends GeneratorCommand
{
    use Building\BuildIndex;
    use Building\BuildModel;
    use Building\BuildResource;

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
        'model_slug' => '',
        'model_singular' => '',
        'model_slug_plural' => '',
        'module' => '',
        'module_slug' => '',
        'title' => '',
        'sections' => '',
        'package' => '',
        'config' => '',
        'form_info_has_one' => '',
        'index_table_columns' => '',
        'index_table_columns_mobile' => '',
        'index_table_columns_standard' => '',
        'sitemap_model_access' => '',
        'sitemap_model_map' => '',
        'sitemap_check_models' => '',
        'sitemap_models' => '',
        'sitemap_title' => '',
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

    protected ?Package $modelPackage = null;

    protected ?ModelRecipe $recipe = null;

    public function prepareOptions(): void
    {
        $this->modelPackage = null;
        $this->recipe = null;

        $options = $this->options();
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$options' => $options,
        //     '$this->c' => $this->c,
        //     '$this->searches' => $this->searches,
        // ]);

        $initModel = false;

        $type = $this->getConfigurationType();

        $model_package = $this->hasOption('model-package') && is_string($this->option('model-package')) ? $this->option('model-package') : '';
        if ($model_package) {
            $this->load_model_package($model_package);
        }

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
                'extends' => 'playground::layouts.resource',
            ]);
            $this->searches['extends'] = 'playground::layouts.resource';
            $initModel = true;

        } elseif (in_array($type, [
            'playground-resource-index',
        ])) {
            $this->c->setOptions([
                'extends' => 'playground::layouts.resource',
            ]);
            $this->searches['extends'] = 'playground::layouts.resource';

            // $title = trim(sprintf('%1$s %2$s', $this->c->module(), $this->c->name()));
            // $module = $this->c->module();
            // if ($module === 'CMS') {
            //     $title = 'Content Management System';
            // } elseif ($module === 'CRM') {
            //     $title = 'Client Relationship Management System';
            // } elseif ($module === 'DAM') {
            //     $title = 'Digital Asset Management System';
            // }
            // $this->c->setOptions([
            //     'title' => $title,
            // ]);
            // $this->searches['title'] = $title;

            $this->build_index_blade();
            $this->build_sitemap_blade();
        }

        if ($initModel) {
            $this->initModel($this->c->skeleton());

            $modelFile = $this->getModelFile();
            if ($modelFile && $this->model?->name()) {
                $this->c->addMappedClassTo(
                    'models',
                    $this->model->name(),
                    $modelFile
                );
            }
        }

        if (! $this->c->model_column() && $this->model?->model_slug()) {
            $model_slug = $this->model->model_slug();
            $model_singular = $this->model->model_singular();
            $model_slug_plural = $this->model->model_slug_plural();

            $this->c->setOptions([
                'model_column' => Str::of($this->model->model_slug())->replace('-', '_')->toString(),
                'model_singular' => $model_singular,
                'model_slug' => $model_slug,
                'model_slug_plural' => $model_slug_plural,
                'model_label' => $model_singular,
            ]);
            $this->searches['model_column'] = $this->c->model_column();
            $this->searches['model_singular'] = $model_singular;
            $this->searches['model_slug'] = $model_slug;
            $this->searches['model_slug_plural'] = $model_slug_plural;
            $this->searches['model_label'] = $model_singular;
        }

        if (in_array($type, [
            'playground-resource',
        ])) {
            if ($this->model) {
                $this->recipe = $this->model->getRecipe();
                $this->build_index_table_columns($this->model);
            }
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

    public function load_model_package(string $model_package): void
    {
        $payload = $this->readJsonFileAsArray($model_package);
        if (! empty($payload)) {
            $this->modelPackage = new Package($payload);
            // $this->modelPackage->apply();
        }
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     */
    protected function qualifyClass($name): string
    {
        $type = $this->getConfigurationType();
        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$type' => $type,
        //     '$this->c' => $this->c,
        //     '$this->searches' => $this->searches,
        //     '$this->options()' => $this->options(),
        // ]);

        if (! $this->c->folder()) {
            $this->c->setOptions([
                'folder' => Str::of($name)->kebab()->toString(),
            ]);
            $this->searches['folder'] = $this->c->folder();
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
        //     '$this->c' => $this->c,
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
            $this->create_playground_resources();
        } elseif (in_array($type, [
            'playground-resource-index',
        ])) {
            $this->create_sitemap_blade();
        }

        $this->saveConfiguration();

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$type' => $type,
        //     '$this->c' => $this->c,
        //     '$this->searches' => $this->searches,
        //     '$this->options()' => $this->options(),
        // ]);
        return $this->return_status;
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
        $options[] = ['model-package', null, InputOption::VALUE_OPTIONAL, 'The model package to use for loading migrations'];
        $options[] = ['revision', null, InputOption::VALUE_NONE, 'Enable revisions for the '.strtolower($this->type).' type'];

        return $options;
    }
}
