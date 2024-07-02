<template>
    <input class="rounded-md border-2 border-gray-300 text-gray-700 py-2 px-3 w-full"
           v-mask="mask"
           :value="modelValue" @input="$emit('update:modelValue', $event.target.value)" 
           ref="input">
</template>

<script>
    export default {
        props: ['modelValue', 'mask'],

        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>
