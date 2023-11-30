<script>
import { Inertia } from '@inertiajs/inertia'


export default {
    props: ['filters', ],
    data() {
        return {
            selectedDate: this.filters.date || '' ,
        }
    },
    methods: {
        applyFilter() {
            const currentFilters = Object.assign({}, this.filters)
            const newFilters = { page: 1, }
            if (this.selectedDate) {
                newFilters.date = this.selectedDate
            } else {
                delete currentFilters.date
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
        <label for="filter-date" class="filter__title">Выберите месяц оплаты</label>
        <input
            type="date"
            v-model="selectedDate"
            id="filter-date"
            @input="applyFilter"
        />
    </div>
</template>
