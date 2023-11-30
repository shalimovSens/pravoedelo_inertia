<script>
import { Inertia } from '@inertiajs/inertia'


export default {
    props: ['value', ],
}
</script>
<template>
    <td class="cell text-center border-2 xl:p-2 p-px">
        {{ value }}
    </td>
</template>
