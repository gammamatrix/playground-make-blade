<div class="card">
    <div class="card-header">
        <h2>{{ __("Parent") }}</h2>
    </div>

    @if ($data->parent_id)
        @php
            $parent = $data->parent()->first();
        @endphp

        @if ($parent)
            <div class="card-body">
                {{ $parent->name }}
            </div>
        @endif
    @elseif (! $data->locked && $routePatch)
        @php
            $parents = [];
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
                    aria-label=" {{ __("Parent Form") }}"
                >
                    <div class="input-group w-100">
                        <select
                            class="form-select"
                            aria-label=" {{ __("set the parent") }}"
                            name="parent_id"
                            required
                        >
                            <option selected value="">
                                {{ __("set the parent") }}
                            </option>
                            @foreach ($parents as $parent)
                                <option value="{{ $parent->id }}">
                                    {{ $parent->label ?: $parent->title }}
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
</div>
