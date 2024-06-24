<template>
    <input class="rounded-md border border-solid border-black text-gray-700 py-2 px-3"
        :value="modelValue" 
        @input="$emit('update:modelValue', $event.target.value)" 
        ref="input" 
        :maxlength="maxlength || 1000">
</template>

<script>
    export default {
        props: ['modelValue', 'maxlength'],
        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>
