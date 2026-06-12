<x-core::header :$model :back-url="$model->indexUrl()" :back-label="__('Events')" :default-title="__('New event')" />

<div class="form-body">
    <x-core::form-errors />

    <div class="row">
        <div class="col-lg-8">
            <x-core::title-and-slug-fields />
            <div class="mb-3">
                <x-transbootform::checkbox :label="__('Published')" name="status" :unchecked-value="0" />

                <x-bootform::checkbox :label="__('Registration form')" name="registration_form" :unchecked-value="0" />
            </div>

            <div class="row gx-3">
                <div class="col-sm-6">
                    <x-bootform::date :label="__('Start date')" name="start_date" :default-value="now()->format('Y-m-d')" required />
                </div>
                <div class="col-sm-6">
                    <x-bootform::date :label="__('End date')" name="end_date" :default-value="now()->format('Y-m-d')" required />
                </div>
            </div>

            <x-transbootform::textarea :label="__('Summary')" name="summary" rows="4" />
            <x-core::tiptap-editors :model="$model" name="body" :label="__('Body')" />
        </div>
        <div class="col-lg-4">
            <div class="right-column">
                <file-manager></file-manager>
                <file-field type="image" field="image_id" :init-file="{{ $model->image ?? 'null' }}"></file-field>
                <file-field type="image" field="og_image_id" :init-file="{{ $model->ogImage ?? 'null' }}" label="@lang('Social Share Image')" hint="1200 × 630 px"></file-field>
                <files-field :init-files="{{ $model->files }}"></files-field>
                <x-transbootform::text :label="__('Venue')" name="venue" />
                <x-transbootform::textarea :label="__('Address')" name="address" rows="3" />
                <x-transbootform::text :label="__('Website')" name="website" type="url" placeholder="https://" />
            </div>
        </div>
    </div>
</div>
