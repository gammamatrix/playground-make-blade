<div class="card">
    <div class="card-header">
        <h2>{{ __("Owner") }}</h2>
    </div>

    @if ($data->owned_by_id)
        @php
            $owner = $data->owner()->first();
        @endphp

        @if ($owner)
            <div class="card-body">
                {{ $owner->name }}
            </div>
        @endif
    @elseif (! $data->locked && $routePatch)
        @php
            $owners = [];
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
                    aria-label=" {{ __("Owner Form") }}"
                >
                    <div class="input-group w-100">
                        <select
                            class="form-select"
                            aria-label=" {{ __("set the owner") }}"
                            name="owned_by_id"
                            required
                        >
                            <option selected value="">
                                {{ __("set the owner") }}
                            </option>
                            @foreach ($owners as $owner)
                                <option value="{{ $owner->id }}">
                                    {{ $owner->name }}
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
