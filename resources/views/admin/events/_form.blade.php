<x-core::header :$model :back-url="$model->indexUrl()" :back-label="__('Events')" :default-title="__('New event')" />

<div class="form-body">
    <x-core::form-errors />

    <div class="row">
        <div class="col-lg-8">
            <x-core::title-and-slug-fields />
            <div class="mb-3">
                {!! TranslatableBootForm::hidden('status')->value(0) !!} {!! TranslatableBootForm::checkbox(__('Published'), 'status') !!} {!! BootForm::hidden('registration_form')->value(0) !!} {!! BootForm::checkbox(__('Registration form'), 'registration_form') !!}
            </div>

            <div class="row gx-3">
                <div class="col-sm-6">
                    {!!
                        BootForm::date(__('Start date'), 'start_date')
                            ->value(old('start_date') ?: ($model->start_date ?: now())->format('Y-m-d'))
                            ->required()
                    !!}
                </div>
                <div class="col-sm-6">
                    {!!
                        BootForm::date(__('End date'), 'end_date')
                            ->value(old('end_date') ?: ($model->end_date ?: now())->format('Y-m-d'))
                            ->required()
                    !!}
                </div>
            </div>

            {!! TranslatableBootForm::text(__('Venue'), 'venue') !!} {!! TranslatableBootForm::textarea(__('Address'), 'address')->rows(3) !!} {!! TranslatableBootForm::text(__('Website'), 'website')->type('url')->placeholder('https://') !!} {!! TranslatableBootForm::textarea(__('Summary'), 'summary')->rows(4) !!}
            <x-core::tiptap-editors :model="$model" name="body" :label="__('Body')" />
        </div>
        <div class="col-lg-4">
            <div class="right-column">
                <file-manager></file-manager>
                <file-field type="image" field="image_id" :init-file="{{ $model->image ?? 'null' }}"></file-field>
                <file-field type="image" field="og_image_id" :init-file="{{ $model->ogImage ?? 'null' }}" label="@lang('Social Share Image')" hint="1200 × 630 px"></file-field>
                <files-field :init-files="{{ $model->files }}"></files-field>
            </div>
        </div>
    </div>
</div>
