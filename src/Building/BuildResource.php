<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Blade\Building;

use Illuminate\Support\Str;
use Playground\Make\Configuration\Model;

/**
 * \Playground\Make\Blade\Building\BuildResource
 */
trait BuildResource
{
    /**
     * @var array<int, string>
     */
    protected array $standard_columns = [];

    /**
     * @var array<int, string>
     */
    protected array $mobile_columns = [];

    /**
     * @var array<int, string>
     */
    protected array $viewable_columns = [];

    protected function build_index_blade(): void
    {
        $models = $this->modelPackage?->models() ?? [];

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$models' => $models,
        //     '$this->c->type()' => $this->c->type(),
        //     '$this->options()' => $this->options(),
        // ]);

        if (! $this->c->route()) {
            $this->components->error('Blades: A route is required to build the index.');

            return;
        }

        $models = $this->modelPackage?->models() ?? [];
        foreach ($models as $model => $file) {
            if (is_string($file) && $file) {

                $model = new Model($this->readJsonFileAsArray($file));

                if ($model->revision()) {
                    // Revisions are handled by the base model.
                    continue;
                }

                // $params_controller['--model'] = $model->name();
                // $params_controller['name'] = Str::of($model->name())->studly()->finish('Controller')->toString();
                // $params_controller['--model-file'] = $file;

                // dump([
                //     '__METHOD__' => __METHOD__,
                //     // '$params_controller' => $params_controller,
                //     // '$this->c' => $this->c,
                //     '$model->name()' => $model->name(),
                //     '$model->revision()' => $model->revision(),
                //     // '$model' => $model,
                //     // '$this->c' => $this->c,
                // ]);
                $this->build_index_blade_section($model);
            }
        }
    }

    protected function build_index_blade_section(Model $model): void
    {
        $model_label_plural = $model->model_plural();
        if (! $model_label_plural) {
            $model_label_plural = Str::of($model->name())->kebab()->replace('-', ' ')->plural()->toString();
        }

        $model_label_lower_plural = Str::of($model_label_plural)->lower()->toString();
        $model_snake_plural = Str::of($model_label_plural)->snake()->toString();

        // $model_slug = $model->model_slug();
        // if (!$model_slug) {
        //     $model_slug = Str::of($model->name())->kebab()->toString();
        // }

        $model_route = sprintf('%1$s.%2$s', $this->c->route(), $model_snake_plural);

        $this->searches['sections'] .= <<<PHP_CODE

                        <div class="col-sm-6">
                            <div class="card m-1">
                                <div class="card-body">
                                    <h5 class="card-title">$model_label_plural</h5>
                                    <h6 class="card-subtitle mb-2 text-muted">Manage $model_label_lower_plural</h6>
                                    <p class="card-text"></p>
                                    <a class="card-link" href="{{ route('$model_route') }}">View $model_label_plural</a>
                                </div>
                            </div>
                        </div>
PHP_CODE;
    }

    protected function build_sitemap_blade(): void
    {
        $models = $this->modelPackage?->models() ?? [];

        $models_check_list = [];
        $models_list = [];

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$models' => $models,
        //     '$this->c->type()' => $this->c->type(),
        //     '$this->options()' => $this->options(),
        // ]);

        if (! $this->c->route()) {
            $this->components->error('Blades: A route is required to build the sitemap.');

            return;
        }

        $title = trim(sprintf('%1$s %2$s', $this->c->module(), $this->c->name()));
        $module = $this->c->module();
        if ($module === 'CMS') {
            $title = 'Content Management System';
        } elseif ($module === 'CRM') {
            $title = 'Client Relationship Management System';
        } elseif ($module === 'DAM') {
            $title = 'Digital Asset Management System';
        }

        $this->searches['sitemap_title'] = $title;

        $models = $this->modelPackage?->models() ?? [];
        foreach ($models as $model => $file) {
            if (is_string($file) && $file) {

                $model = new Model($this->readJsonFileAsArray($file));

                if ($model->revision()) {
                    // Revisions are handled by the base model.
                    continue;
                }

                if ($model->model_plural()) {
                    $models_list[] = strtolower($model->model_plural());
                }
                if ($model->model_slug_plural()) {
                    $models_check_list[] = Str::of($model->model_plural())->studly()->start('!$view')->toString();
                }
                // $params_controller['--model'] = $model->name();
                // $params_controller['name'] = Str::of($model->name())->studly()->finish('Controller')->toString();
                // $params_controller['--model-file'] = $file;

                // dump([
                //     '__METHOD__' => __METHOD__,
                //     // '$params_controller' => $params_controller,
                //     // '$this->c' => $this->c,
                //     '$model->name()' => $model->name(),
                //     '$model->revision()' => $model->revision(),
                //     // '$model' => $model,
                //     // '$this->c' => $this->c,
                // ]);
                $this->build_sitemap_blade_section($model);
            }
        }
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$models_list' => $models_list,
        //     '$this->c->type()' => $this->c->type(),
        // ]);

        if (count($models_list) > 1) {

            $last = array_pop($models_list);
            $this->searches['sitemap_models'] = implode(', ', $models_list);
            $this->searches['sitemap_models'] .= ' and '.$last;
        } else {
            $this->searches['sitemap_models'] = implode(', ', $models_list);
        }

        $this->searches['sitemap_check_models'] = implode(' && ', $models_check_list);
    }

    protected function build_sitemap_blade_section(Model $model): void
    {
        $roles = ['admin', 'manager', 'publisher'];

        $roles_text = implode("', '", $roles);

        $package = $this->c->package();

        $model_label_plural = $model->model_plural();
        if (! $model_label_plural) {
            $model_label_plural = Str::of($model->name())->kebab()->replace('-', ' ')->plural()->toString();
        }

        $model_studly_plural = Str::of($model_label_plural)->studly()->toString();
        $model_snake_plural = Str::of($model_label_plural)->snake()->toString();
        $model_label_lower_plural = Str::of($model_label_plural)->lower()->toString();

        $model_slug = $model->model_slug();
        if (! $model_slug) {
            $model_slug = Str::of($model->name())->kebab()->toString();
        }

        $model_route = sprintf('%1$s.%2$s', $this->c->route(), $model_snake_plural);

        $this->searches['sitemap_model_access'] .= <<<PHP_CODE

\$view{$model_studly_plural} = \Playground\Auth\Facades\Can::access(\$user, [
    'allow' => false,
    'any' => true,
    'privilege' => '{$package}:{$model_slug}:viewAny',
    'roles' => ['{$roles_text}'],
])->allowed();

PHP_CODE;

        $this->searches['sitemap_model_map'] .= <<<PHP_CODE

                        @if (\$view{$model_label_plural})
                        <a href="{{ route('{$model_route}') }}" class="list-group-item list-group-item-action">
                            $model_label_plural
                        </a>
                        @endif

PHP_CODE;
    }

    protected function create_sitemap_blade(): void
    {
        /**
         * @var array<string, string> $blades
         */
        $blades = [];

        $blades['sitemap.blade.php'] = 'blade/playground/resource/sitemap.blade.php.stub';

        foreach ($blades as $blade => $source) {

            // $path_stub = 'blade'.$blade;
            $path = $this->resolveStubPath($source);

            $destination = sprintf(
                '%1$s/%2$s',
                $this->folder(),
                $blade
            );
            // dd([
            //     '__METHOD__' => __METHOD__,
            //     '$source' => $source,
            //     '$blade' => $blade,
            //     '$destination' => $destination,
            //     '$this->folder' => $this->folder(),
            //     '$this->c' => $this->c,
            //     '$this->searches' => $this->searches,
            // ]);
            $stub = $this->files->get($path);

            $this->search_and_replace($stub);

            $full_path = $this->laravel->storagePath().$destination;
            $this->files->put($full_path, $stub);

            $this->components->info(sprintf('Blade: %s [%s] created successfully.', $blade, $full_path));
        }
    }
}
