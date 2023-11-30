<script>
import { Inertia } from '@inertiajs/inertia'


export default {
    props: ['firtsValue', 'secondValue', 'clientId', ],
    data() {
        return {
            // newFirstValue: '',
            // newSecondValue: '',
            savedValue: '',
            newValue: '',
            isEdit: false,
        }
    },
    methods: {
        handleClick(value) {
            this.isEdit = true

            this.newValue = value
            this.savedValue = value

            if (this.isEdit) {
                window.addEventListener('click', this.closeInput)
            } else {
                window.removeEventListener('click', this.closeInput)
            }
        },
        closeInput(e) {
            if (this.$refs.cell.contains(e.target)) return

            this.isEdit = false
            this.newValue = ''
            window.removeEventListener('click', this.closeInput)
        },
        handleSave() {
            if (!this.newValue) return

            const newClientData = {
                id: this.clientId,
            }

            if (this.savedValue === this.firtsValue) {
                newClientData.first_date = parseInt(this.newValue)
            } else if (this.savedValue === this.secondValue) {
                newClientData.second_date = parseInt(this.newValue)
            } else {
                return
            }

            Inertia.post('/update-data', {
                data: newClientData
            })

            this.isEdit = false
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
            @click="handleClick(firtsValue)"
            class="cursor-pointer"
        >
            {{ firtsValue }}
        </span>
        <span v-show="!isEdit">-</span>
        <span
            v-show="!isEdit"
            @click="handleClick(secondValue)"
            class="cursor-pointer"
        >
            {{ secondValue }}
        </span>
        <span
            v-show="isEdit"
            class="inline-flex items-center gap-x-4"
        >
            <input
                v-model="newValue"
                class="w-8"
            />
            <button
                class="bg-red-900 rounded p-1"
                @click="handleSave"
            >
                <svg xmlns="http://www.w3.org/2000/svg" height="16" width="14" viewBox="0 0 448 512"><path fill="#ffffff" d="M438.6 105.4c12.5 12.5 12.5 32.8 0 45.3l-256 256c-12.5 12.5-32.8 12.5-45.3 0l-128-128c-12.5-12.5-12.5-32.8 0-45.3s32.8-12.5 45.3 0L160 338.7 393.4 105.4c12.5-12.5 32.8-12.5 45.3 0z"/></svg>
            </button>
        </span>
    </td>
</template>
