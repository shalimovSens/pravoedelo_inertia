<script>
import { Inertia } from '@inertiajs/inertia'


export default {
    props: ['value', 'clientId', ],
    data() {
        return {
            newValue: this.value,
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
            if (!this.newValue) return

            const names = this.newValue.trim().split(' ');
            console.log(names)
            if (names.length !== 3) {
                alert('Введите ФИО')
                return
            }

            const newClientData = {
                id: this.clientId,
                first_name: names[1],
                last_name: names[0],
                middle_name: names[2],
            }
            console.log(newClientData)

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
            @click="handleClick"
            class="cursor-pointer"
        >
            {{ value }}
        </span>
        <span
            v-show="isEdit"
            class="inline-flex items-center gap-x-4"
        >
            <input
                v-model="newValue"
                class="w-20"
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
