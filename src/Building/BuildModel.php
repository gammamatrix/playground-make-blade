<?php

/**
 * Playground
 */

declare(strict_types=1);

namespace Playground\Make\Blade\Building;

use Playground\Make\Blade\Console\Commands\BladeMakeCommand;
use Playground\Make\Configuration\Model;

/**
 * \Playground\Make\Blade\Building\BuildModel
 *
 * @mixin BladeMakeCommand
 */
trait BuildModel
{
    protected function create_playground_resources(): void
    {
        if (empty($this->model)) {
            return;
        }

        $revision = $this->hasOption('revision') && $this->option('revision');

        $this->build_detail_flags($this->model);
        $this->build_detail_manage_blades();
        $this->build_detail_manage_ids($this->model);

        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$revision' => $revision,
        //     '$this->c->type()' => $this->c->type(),
        //     '$this->folder' => $this->folder(),
        //     '$this->c' => $this->c,
        //     '$this->model' => $this->model,
        //     '$this->options()' => $this->options(),
        // ]);
        /**
         * @var array<string, string> $blades
         */
        $blades = [];

        $blades['detail.blade.php'] = 'blade/playground/resource/model/detail.blade.php.stub';
        if ($revision) {
            $blades['form.blade.php'] = 'blade/playground/resource/model/form-with-revision.blade.php.stub';
        } else {
            $blades['form.blade.php'] = 'blade/playground/resource/model/form.blade.php.stub';
        }
        $blades['form-info.blade.php'] = 'blade/playground/resource/model/form-info.blade.php.stub';
        //        $blades['form-dates.blade.php'] = 'blade/playground/resource/model/form-dates.blade.php.stub';
        //        $blades['form-flags.blade.php'] = 'blade/playground/resource/model/form-flags.blade.php.stub';
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

    protected function build_detail_manage_blades(): void
    {
        // TODO this does not need to be build on every model

        $pathToIo = $this->laravel->storagePath().$this->getPackageFolder().'/resources/views/io';
        //        dd([
        //            '__METHOD__' => __METHOD__,
        //            '$pathToIo' => $pathToIo,
        //            '$this->folder' => $this->folder(),
        //        ]);
        if (! is_dir($pathToIo)) {
            mkdir($pathToIo);
        }

        /**
         * @var array<string, string> $blades
         */
        $blades = [];

        $blades['manage-matrix.blade.php'] = 'blade/playground/resource/io/manage-matrix.blade.php';
        $blades['manage-owner.blade.php'] = 'blade/playground/resource/io/manage-owner.blade.php';
        $blades['manage-parent.blade.php'] = 'blade/playground/resource/io/manage-parent.blade.php';

        foreach ($blades as $blade => $source) {

            // $path_stub = 'blade'.$blade;
            $path = $this->resolveStubPath($source);

            $destination = sprintf(
                '%1$s/io/%2$s',
                $this->folder(),
                $blade
            );
            //             dd([
            //                 '__METHOD__' => __METHOD__,
            //                 '$source' => $source,
            //                 '$path' => $path,
            //                 '$destination' => $destination,
            //                 '$this->folder' => $this->folder(),
            //                 '$this->c' => $this->c,
            //             ]);
            $stub = $this->files->get($path);

            $this->search_and_replace($stub);

            $full_path = $this->laravel->storagePath().$destination;
            $this->files->put($full_path, $stub);

            $this->components->info(sprintf('Blade: %s [%s] created successfully.', $blade, $full_path));
        }

    }
}
