<x-core::layouts.admin :title="'Registrations > ' . $event->title">
    <item-list
        url-base="/api/events/{{ $event->id }}/registrations"
        fields="id,event_id,created_at,first_name,last_name,email,locale,number_of_people,message"
        table="registrations"
        title="registrations"
        :show-title="false"
        :exportable="true"
        :publishable="false"
        :translatable="false"
        :searchable="['created_at,first_name,last_name,email,locale,number_of_people,message']"
        :sorting="['-created_at']"
    >
        <template #back-button>
            <x-core::back-button :back-url="$event->indexUrl()" :back-label="__('Events')" />
        </template>

        <template #columns="{ sortArray }">
            <item-list-column-header name="checkbox"></item-list-column-header>
            <item-list-column-header name="edit"></item-list-column-header>
            <item-list-column-header name="created_at" sortable :sort-array="sortArray" :label="$t('Date')"></item-list-column-header>
            <item-list-column-header name="number_of_people" sortable :sort-array="sortArray" :label="$t('# people')"></item-list-column-header>
            <item-list-column-header name="first_name" sortable :sort-array="sortArray" :label="$t('First name')"></item-list-column-header>
            <item-list-column-header name="last_name" sortable :sort-array="sortArray" :label="$t('Last name')"></item-list-column-header>
            <item-list-column-header name="email" sortable :sort-array="sortArray" :label="$t('Email')"></item-list-column-header>
            <item-list-column-header name="locale" sortable :sort-array="sortArray" :label="$t('Lang')"></item-list-column-header>
            <item-list-column-header name="message" sortable :sort-array="sortArray" :label="$t('Message')"></item-list-column-header>
        </template>

        <template #table-row="{ model, checkedModels, loading }">
            <td class="checkbox">
                <item-list-checkbox :model="model" :checked-models-prop="checkedModels" :loading="loading"></item-list-checkbox>
            </td>
            <td v-if="$can('update-registration')">
                <item-list-edit-button :url="'/admin/events/' + model.event_id + '/registrations/' + model.id + '/edit'"></item-list-edit-button>
            </td>
            <td>
                <small class="text-muted text-norap">@{{ formatDateTime(model.created_at) }}</small>
            </td>
            <td>@{{ model.number_of_people }}</td>
            <td>@{{ model.first_name }}</td>
            <td>@{{ model.last_name }}</td>
            <td>
                <a :href="'mailto:' + model.email">@{{ model.email }}</a>
            </td>
            <td>
                <span class="badge text-bg-secondary">@{{ model.locale.toUpperCase() }}</span>
            </td>
            <td>@{{ model.message }}</td>
        </template>
    </item-list>
</x-core::layouts.admin>
