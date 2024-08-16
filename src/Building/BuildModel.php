<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Blade\Building;

use Playground\Make\Configuration\Model;

/**
 * \Playground\Make\Blade\Building\BuildModel
 */
trait BuildModel
{
    protected function create_playground_resources(): void
    {
        $revision = $this->hasOption('revision') && $this->option('revision');

        // dump([
        //     '__METHOD__' => __METHOD__,
        //     '$revision' => $revision,
        //     '$this->c->type()' => $this->c->type(),
        //     '$this->folder' => $this->folder(),
        //     // '$this->c' => $this->c,
        //     // '$this->model' => $this->model,
        //     '$this->options()' => $this->options(),
        // ]);
        /**
         * @var array<string, string> $blades
         */
        $blades = [];

        $blades['detail.blade.php'] = 'blade/playground/resource/model/detail.blade.php.stub';
        if ($revision) {
            $blades['form-with-revision.blade.php'] = 'blade/playground/resource/model/form.blade.php.stub';
        } else {
            $blades['form.blade.php'] = 'blade/playground/resource/model/form.blade.php.stub';
        }
        $blades['form-info.blade.php'] = 'blade/playground/resource/model/form-info.blade.php.stub';
        $blades['form-publishing.blade.php'] = 'blade/playground/resource/model/form-publishing.blade.php.stub';
        $blades['form-status.blade.php'] = 'blade/playground/resource/model/form-status.blade.php.stub';
        // $blades['index'] = 'blade/playground/resource/model/index.blade.php.stub';

        if ($revision) {
            $blades['revision.blade.php'] = 'blade/playground/resource/model/revision.blade.php.stub';
            $blades['form-revisions.blade.php'] = 'blade/playground/resource/model/form-revisions.blade.php.stub';
            $blades['revisions.blade.php'] = 'blade/playground/resource/model/revisions.blade.php.stub';
        }

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
}
