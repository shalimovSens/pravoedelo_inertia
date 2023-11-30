<script>
import { Inertia } from '@inertiajs/inertia'
// т.к. только список клиентов - это массив, приходится делать другой компонент

export default {
    props: ['title', 'clients', 'filters', ],
    data() {
        return {
            selectedClient: this.filters.client_id || '',
        }
    },
    methods: {
        applyFilter() {
            const currentFilters = Object.assign({}, this.filters)
            const newFilters = { page: 1, }

            if (this.selectedClient) {
                newFilters.client_id = this.selectedClient
            } else {
                delete currentFilters.client_id
                // delete currentFilters.page
            }

            Inertia.visit('/table', {
                data: Object.assign(currentFilters, newFilters),
            })
        },
    },
}
</script>
<template>
    <div class="filter flex items-center gap-x-4 mb-4 flex-wrap">
        <label for="filter-client" class="filter__title">{{ title }}</label>
        {{ selectedClient }}
        <select
            v-model="selectedClient"
            id="filter-client"
            @change="applyFilter"
        >
            <option value="">Все</option>
            <option
                v-for="client in clients"
                :key="client.id"
                :value="client.id"
            >
                {{ `${client.last_name} ${client.first_name} ${client.middle_name}` }}
            </option>
        </select>
    </div>
</template>
