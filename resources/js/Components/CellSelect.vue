<script>
import { Inertia } from '@inertiajs/inertia'


export default {
    props: ['value', 'clientId', 'options', ],
    data() {
        return {
            selectedOption: this.value,
            isEdit: false,
        }
    },
    methods: {
        handleClick() {
            this.isEdit = !this.isEdit

            if (this.isEdit) {
                window.addEventListener('click', this.closeInput)
            } else {
                window.removeEventListener('click', this.closeInput)
            }
        },
        closeInput(e) {
            if (this.$refs.cell.contains(e.target)) return

            this.isEdit = false
            this.newValue = this.value
            window.removeEventListener('click', this.closeInput)
        },
        handleSave() {

            if (!this.selectedOption) return


            const newClientData = {
                id: this.clientId,
            }
            console.log(newClientData)

            // Inertia.post('/update-data', {
            //     data: newClientData
            // })

            this.isEdit = false
        },
        applyFilter() {
            //
        },
    }
}
</script>
<template>
    <td
        class="cell text-center border-2 xl:p-2 p-px"
        ref="cell"
    >
        <span
            v-show="!isEdit"
            @click="handleClick"
            class="cursor-pointer"
        >
            {{ value }}
        </span>
        <span
            v-show="isEdit"
        >
        <!-- class="inline-flex items-center gap-x-4" -->
            <select
                class="w-20"
                v-model="selectedOption"
                @change="applyFilter"
            >
                <option
                    v-for="(field, key) in options"
                    :key="key"
                    :value="key"
                >
                    {{ field, key }}
                </option>
            </select>
        </span>
    </td>
</template>
