<script>
import { Inertia } from '@inertiajs/inertia'


export default {
    props: ['title', 'options', 'query', 'filters', ],
    data() {
        return {
            selectedOption: this.filters[this.query] || '' ,
        }
    },
    methods: {
        applyFilter() {
            const currentFilters = Object.assign({}, this.filters)
            const newFilters = { page: 1, }
            if (this.selectedOption) {
                newFilters[this.query] = this.selectedOption
            } else {
                delete currentFilters[this.query]
            }

            Inertia.visit('/table', {
                data: Object.assign(currentFilters, newFilters),
            })
        },
    },
}
</script>
<template>
    <div class="filter flex items-center gap-4 mb-4 flex-wrap">
        <label :for="`filter-${query}`" class="filter__title">{{ title }}</label>
        <select
            v-model="selectedOption"
            :id="`filter-${query}`"
            @change="applyFilter"
        >
            <option value="">Все</option>
            <option
                v-for="(field, key) in options"
                :key="key"
                :value="key"
            >
                {{ field, key }}
            </option>
        </select>
    </div>
</template>
