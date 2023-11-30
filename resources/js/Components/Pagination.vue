<script>
import { Inertia } from '@inertiajs/inertia'


export default {
    props: ['currentPage', 'lastPage', 'filters'],
    methods: {
        handleNextPage() {
            this.isDisabled = true

            if (this.isLastPage) return

            const currentFilters = Object.assign({}, this.filters)

            Inertia.visit('/table', {
                data: Object.assign(currentFilters, { page: this.currentPage + 1 }),
                preserveScroll: true,
            })

            this.isDisabled = false
        },
        handlePrevPage() {
            this.isDisabled = true

            if (this.isFirstPage) return

            const currentFilters = Object.assign({}, this.filters)

            Inertia.visit('/table', {
                data: Object.assign(currentFilters, { page: this.currentPage - 1 }),
                preserveScroll: true,
            })

            this.isDisabled = false
        },
    },
    data() {
        return {
            isLastPage: this.currentPage >= this.lastPage,
            isFirstPage: this.currentPage <= 1,
            isDisabled: false,
        }
    },
}
</script>
<template>
    <div class="pagination flex flex-row items-center gap-x-1">
        <button
            class="pagination__button md:h-14 h-10 bg-red-900 text-white rounded md:px-4 px-1"
            @click="handlePrevPage"
            :disabled="isFirstPage || isDisabled"
        >
            Назад
        </button>
        <span class="pagination_status inline-flex items-center md:h-14 h-10 border-2 border-red-900 rounded md:px-4 px-2 text-base font-bold">
            {{ currentPage }}
        </span>
        <button
            class="pagination__button md:h-14 h-10 bg-red-900 text-white rounded md:px-4 px-1"
            @click="handleNextPage"
            :disabled="isLastPage || isDisabled"
        >
            Далее
        </button>
    </div>
</template>
