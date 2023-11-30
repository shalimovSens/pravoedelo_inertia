<script>


export default {
    props: ['value',],
}
</script>
<template>
    <th
        class="cell text-center border-2 border-red-900 xl:p-4 p-2"
    >
        {{ value }}
    </th>
</template>
