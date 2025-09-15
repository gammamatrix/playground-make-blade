<?php

$withMatrix = class_exists("Playground\\Matrix\\Models\\Matrix");
?>

<div class="card">
    <div class="card-header">
        @if (! empty($modelColumn) && ! empty($modelLabel) && Illuminate\Support\Facades\Route::has("playground.matrix.resource.matrices.create"))
            <a
                class="btn btn-info float-end"
                href="{{ route("playground.matrix.resource.matrices.create", [$modelColumn => $data->id, "_return_url" => $routeShow]) }}"
                alt=" {{ __("Create a new matrix for the :model_label", ["model_label" => $modelLabel]) }}"
            >
                {{ __("Create") }}
            </a>
        @endif

        <h2>{{ __("Matrix") }}</h2>
    </div>

    @if ($data->matrix_id)
        @php
            $matrix = $data->matrix()->first();
        @endphp

        @if ($matrix)
            <div class="card-body">
                @if (Illuminate\Support\Facades\Route::has("playground.matrix.resource.matrices.show"))
                    <a
                        href="{{ route("playground.matrix.resource.matrices.show", ["matrix" => $matrix->id]) }}"
                        alt="{{ $matrix->description }}"
                    >
                        {{ $matrix->title }}
                    </a>
                @else
                    {{ $matrix->title }}
                @endif
            </div>
            @if ($matrix->matrix_type)
                <div class="card-footer">
                    {{ $matrix->matrix_type }}
                </div>
            @endif
        @endif
    @elseif (! $data->locked && $routePatch)
        @if ($withMatrix)
            @php
                $matrices = Playground\Matrix\Models\Matrix::all();
            @endphp

            <div class="card-body">
                <form
                    method="POST"
                    action="{{ $routePatch }}"
                    novalidate
                    class="needs-validation"
                >
                    @csrf
                    @method("patch")
                    <div
                        class="btn-toolbar mb-3"
                        role="toolbar"
                        aria-label="{{ __("Matrix Form") }}"
                    >
                        <div class="input-group w-100">
                            <select
                                class="form-select"
                                aria-label="{{ __("set the matrix") }}"
                                name="matrix_id"
                                required
                            >
                                <option selected value="">
                                    {{ __("set the matrix") }}
                                </option>
                                @foreach ($matrices as $matrix)
                                    <option value="{{ $matrix->id }}">
                                        {{ $matrix->title ?: $matrix->label }}
                                    </option>
                                @endforeach
                            </select>
                            <button class="btn btn-success" role="button">
                                {{ __("Save") }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    @endif
</div>
