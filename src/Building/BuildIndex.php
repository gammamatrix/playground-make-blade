<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Blade\Building;

use Illuminate\Support\Str;
use Playground\Make\Configuration\Model;

/**
 * \Playground\Make\Blade\Building\BuildIndex
 */
trait BuildIndex
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

    protected function build_index_table_columns(Model $model): void
    {
        $this->standard_columns = [];
        $this->mobile_columns = [];
        $this->viewable_columns = [];

        $this->build_index_table_columns_standard($model);
        $this->build_index_table_columns_mobile($model);

        $this->searches['index_table_columns'] = '';

        $this->build_index_table_column_ids($model);
        $this->build_index_table_column_columns($model);
        $this->build_index_table_column_ui($model);
        $this->build_index_table_column_flags($model);

        $this->searches['index_table_columns'] .= <<<'PHP_CODE'
    'created_at' => [
        'hide-sm' => true,
        'label' => 'Created at',
    ],
    'updated_at' => [
        'hide-sm' => true,
        'label' => 'Updated at',
    ],

PHP_CODE;

        $this->build_index_table_column_dates($model);
        $this->build_index_table_column_permissions($model);
        $this->build_index_table_column_status($model);
        if ($this->recipe?->index()->addMatrix()) {
            $this->build_index_table_column_matrix($model);
        }
    }

    protected function build_index_table_columns_standard(Model $model): void
    {
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     // '$recipe' => $recipe,
        //     '$recipe->index()' => $recipe->index(),
        // ]);

        $this->standard_columns = $this->recipe?->index()->columnsStandard() ?? [];

        $ids = $model->create()?->ids() ?? [];
        $model_type = Str::of($model->model_slug())->replace('-', '_')->finish('_type')->toString();
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$ids' => $ids,
        //     '$model_type' => $model_type,
        // ]);

        if ($this->recipe?->index()->addModelType()
            && ! empty($ids[$model_type])
            && ! in_array($model_type, $this->standard_columns)
        ) {
            // array_unshift($this->standard_columns, $model_type);
            array_splice($this->standard_columns, 1, 0, $model_type);
        }

        $this->searches['index_table_columns_standard'] = '';

        foreach ($this->standard_columns as $column) {
            $this->searches['index_table_columns_standard'] .= sprintf('    \'%1$s\',', $column).PHP_EOL;
        }
    }

    protected function build_index_table_columns_mobile(Model $model): void
    {
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     // '$recipe' => $recipe,
        //     '$recipe->index()' => $recipe->index(),
        // ]);

        $this->mobile_columns = $this->recipe?->index()->columnsMobile() ?? [];

        $ids = $model->create()?->ids() ?? [];
        $model_type = Str::of($model->model_slug())->replace('-', '_')->finish('_type')->toString();
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     '$ids' => $ids,
        //     '$model_type' => $model_type,
        // ]);

        if ($this->recipe?->index()->addModelType()
            && ! empty($ids[$model_type])
            && ! in_array($model_type, $this->mobile_columns)
        ) {
            // $this->mobile_columns[] = $model_type;
            array_splice($this->mobile_columns, 1, 0, $model_type);
        }

        $this->searches['index_table_columns_mobile'] = '';

        foreach ($this->mobile_columns as $column) {
            $this->searches['index_table_columns_mobile'] .= sprintf('    \'%1$s\',', $column).PHP_EOL;
        }
    }

    protected function build_index_table_column_columns(Model $model): void
    {

        $model_attribute = $model->model_attribute();
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     // '$model->create()' => $model->create(),
        //     '$model->create()' => get_class_methods($model->create()),
        // ]);

        $columns = $model->create()?->columns() ?? [];

        foreach ($columns as $column) {

            if ($column->html()) {
                // Skip HTML columns
                continue;
            }
            if (in_array($column->type(), [
                'JSON_ARRAY',
                'JSON_OBJECT',
            ])) {
                // Skip JSON columns
                continue;
            }
            $hideSmall = ! in_array($column->column(), $this->standard_columns) ? 'true' : 'false';
            $linkType = 'null';
            $linkRoute = 'null';

            if ($model_attribute === $column->column()) {
                $linkType = "'id'";
                $linkRoute = "sprintf('%1\$s.show', \$meta['info']['model_route'])";
            }
            // dump([
            //     '__METHOD__' => __METHOD__,
            //     '$column' => $column,
            //     '$hideSmall' => $hideSmall,
            // ]);

            $content = <<<PHP_CODE
    '{$column->column()}' => [
        'hide-sm' => $hideSmall,
        'linkType' => $linkType,
        'linkRoute' => $linkRoute,
        'label' => '{$column->label()}',
    ],

PHP_CODE;

            $this->searches['index_table_columns'] .= $content;
        }
    }

    protected function build_index_table_column_flags(Model $model): void
    {

        $model_attribute = $model->model_attribute();

        $flags = $model->create()?->flags() ?? [];

        foreach ($flags as $column) {
            $hideSmall = ! in_array($column->column(), $this->standard_columns) ? 'true' : 'false';

            // dump([
            //     '__METHOD__' => __METHOD__,
            //     '$column' => $column,
            //     '$hideSmall' => $hideSmall,
            // ]);

            $content = <<<PHP_CODE
    '{$column->column()}' => [
        'hide-sm' => $hideSmall,
        'flag' => true,
        'label' => '{$column->label()}',
        'onTrueClass' => '{$column->icon()}',
    ],

PHP_CODE;

            $this->searches['index_table_columns'] .= $content;
        }
    }

    protected function build_index_table_column_dates(Model $model): void
    {

        $model_attribute = $model->model_attribute();

        $dates = $model->create()?->dates() ?? [];

        foreach ($dates as $column) {
            $hideSmall = ! in_array($column->column(), $this->standard_columns) ? 'true' : 'false';

            // dump([
            //     '__METHOD__' => __METHOD__,
            //     '$column' => $column,
            //     '$hideSmall' => $hideSmall,
            // ]);

            $content = <<<PHP_CODE
    '{$column->column()}' => [
        'hide-sm' => $hideSmall,
        'label' => '{$column->label()}',
    ],

PHP_CODE;

            $this->searches['index_table_columns'] .= $content;
        }
    }

    protected function build_index_table_column_permissions(Model $model): void
    {

        $model_attribute = $model->model_attribute();

        $permissions = $model->create()?->permissions() ?? [];

        foreach ($permissions as $column) {
            $hideSmall = ! in_array($column->column(), $this->standard_columns) ? 'true' : 'false';

            $onTrueClass = '';
            if ($column->type() === 'boolean') {
                $onTrueClass = $column->icon();
            }
            // dump([
            //     '__METHOD__' => __METHOD__,
            //     '$column' => $column,
            //     '$hideSmall' => $hideSmall,
            // ]);

            $content = <<<PHP_CODE
    '{$column->column()}' => [
        'hide-sm' => $hideSmall,
        'label' => '{$column->label()}',
        'onTrueClass' => '{$onTrueClass}',
    ],

PHP_CODE;

            $this->searches['index_table_columns'] .= $content;
        }
    }

    protected function build_index_table_column_status(Model $model): void
    {

        $model_attribute = $model->model_attribute();

        $status = $model->create()?->status() ?? [];

        foreach ($status as $column) {
            $hideSmall = ! in_array($column->column(), $this->standard_columns) ? 'true' : 'false';

            // dump([
            //     '__METHOD__' => __METHOD__,
            //     '$column' => $column,
            //     '$hideSmall' => $hideSmall,
            // ]);

            $content = <<<PHP_CODE
    '{$column->column()}' => [
        'hide-sm' => $hideSmall,
        'label' => '{$column->label()}',
    ],

PHP_CODE;

            $this->searches['index_table_columns'] .= $content;
        }
    }

    protected function build_index_table_column_matrix(Model $model): void
    {

        $model_attribute = $model->model_attribute();

        $matrix = $model->create()?->matrix() ?? [];

        foreach ($matrix as $column) {
            $hideSmall = ! in_array($column->column(), $this->standard_columns) ? 'true' : 'false';

            // dump([
            //     '__METHOD__' => __METHOD__,
            //     '$column' => $column,
            //     '$hideSmall' => $hideSmall,
            // ]);

            $content = <<<PHP_CODE
    '{$column->column()}' => [
        'hide-sm' => $hideSmall,
        'label' => '{$column->label()}',
    ],

PHP_CODE;

            $this->searches['index_table_columns'] .= $content;
        }
    }

    protected function build_index_table_column_ids(Model $model): void
    {

        $model_attribute = $model->model_attribute();

        $ids = $model->create()?->ids() ?? [];

        foreach ($ids as $column) {
            $hideSmall = ! in_array($column->column(), $this->standard_columns) ? 'true' : 'false';

            // dump([
            //     '__METHOD__' => __METHOD__,
            //     '$column' => $column,
            //     '$hideSmall' => $hideSmall,
            // ]);

            $content = <<<PHP_CODE
    '{$column->column()}' => [
        'hide-sm' => $hideSmall,
        'label' => '{$column->label()}',
    ],

PHP_CODE;

            $this->searches['index_table_columns'] .= $content;
        }
    }

    protected function build_index_table_column_ui(Model $model): void
    {

        $model_attribute = $model->model_attribute();
        // dd([
        //     '__METHOD__' => __METHOD__,
        //     // '$model->create()' => $model->create(),
        //     '$model->create()' => get_class_methods($model->create()),
        // ]);

        $ui = $model->create()?->ui() ?? [];

        foreach ($ui as $column) {

            if (in_array($column->type(), [
                'JSON_ARRAY',
                'JSON_OBJECT',
            ])) {
                // Skip JSON columns
                continue;
            }
            $hideSmall = ! in_array($column->column(), $this->standard_columns) ? 'true' : 'false';

            $content = <<<PHP_CODE
    '{$column->column()}' => [
        'hide-sm' => $hideSmall,
        'label' => '{$column->label()}',
    ],

PHP_CODE;

            $this->searches['index_table_columns'] .= $content;
        }
    }
}
