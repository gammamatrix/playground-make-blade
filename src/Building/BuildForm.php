<?php
/**
 * Playground
 */

declare(strict_types=1);
namespace Playground\Make\Blade\Building;

use Illuminate\Support\Str;
use Playground\Make\Configuration\Model;

/**
 * \Playground\Make\Blade\Building\BuildForm
 */
trait BuildForm
{
//     protected function build_form_flags(Model $model): void
//     {
//         $this->searches['detail_flags'] = '';

//         $flags = $model->create()?->flags() ?? [];

//         if (!empty($flags)) {
//             $this->searches['detail_flags'] .= PHP_EOL;
//         }

//         foreach ($flags as $column) {
//             $badge = '';
//             if (in_array($column->column(), $this->detail_flags_danger)) {
//                 $badge = 'text-bg-success';
//             } elseif (in_array($column->column(), $this->detail_flags_info)) {
//                 $badge = 'text-bg-info';
//             } elseif (in_array($column->column(), $this->detail_flags_success)) {
//                 $badge = 'text-bg-success';
//             } elseif (in_array($column->column(), $this->detail_flags_warning)) {
//                 $badge = 'text-bg-warning';
//             }

//             $content = <<<PHP_CODE
//     '{$column->column()}' => ['column' => '{$column->column()}', 'label' => '{$column->label()}', 'icon' => '{$column->icon()}', 'badge' => '$badge'],

// PHP_CODE;

//             $this->searches['detail_flags'] .= $content;
//         }
//     }

//     protected function build_form_ids(Model $model): void
//     {
//         $this->searches['detail_accordion_header'] = '';

//         $package = $this->c->package();

//         $ids = $model->create()?->ids() ?? [];

//         $detail_accordion_header = '';

//         foreach ($ids as $column) {

//             if (!in_array($column->type(), [
//                 'uuid'
//             ]) || in_array($column->column(), [
//                 'created_by_id',
//                 'modified_by_id',
//             ])) {
//                 continue;
//             }

//             if (in_array($column->column(), [
//                 'owned_by_id',
//                 'owner_id',
//             ])) {
//                 $slug = 'owner';
//             } else {
//                 $slug = Str::of($column->column())->before('_id')->slug()->toString();
//             }

//             $content = <<<PHP_CODE
//     <div class="col-sm-6 col-md-4 mb-3">
//         @include('$package::io/manage-{$slug}')
//     </div>

// PHP_CODE;

//             $detail_accordion_header .= $content;
//         }

//         if ($detail_accordion_header) {
//             $this->searches['detail_accordion_header'] = PHP_EOL.'<div class="row mb-3">'.PHP_EOL;
//             $this->searches['detail_accordion_header'] .= $detail_accordion_header;
//             $this->searches['detail_accordion_header'] .= '</div>';
//         }

//     }
}
