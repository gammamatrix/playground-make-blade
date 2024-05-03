<fieldset class="mb-3">

    <legend>Publishing</legend>

    <div class="row">
        <div class="col">
            <x-playground::forms.column type="datetime-local" column="planned_start_at" label="Planned Start"
                described="The date must be after yesterday and before next year."
                :rules="['min' => 'yesterday', 'max' => 'next year']"
            />
        </div>
        <div class="col">
            <x-playground::forms.column type="datetime-local" column="planned_end_at" label="Planned End"/>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <x-playground::forms.column type="datetime-local" column="embargo_at" label="Embargo Until"/>
        </div>
        <div class="col">
            <x-playground::forms.column type="datetime-local" column="published_at" label="Published"/>
        </div>
        <div class="col">
            <x-playground::forms.column type="datetime-local" column="released_at" label="Released"/>
        </div>
    </div>

</fieldset>
