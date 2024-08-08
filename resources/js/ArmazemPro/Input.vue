<template>
    <input class="rounded-md border border-solid border-black text-gray-700 py-2 px-3"
        :value="modelValue" 
        @input="$emit('update:modelValue', $event.target.value)" 
        ref="input" 
        :disabled="disabled"
        :maxlength="maxlength || 1000">
</template>

<script>
    export default {
        props: {
            modelValue: {},
            maxlength: {
                default: 1000
            },
            disabled: {
                type: Boolean,
                default: false
            }
        },
        methods: {
            focus() {
                this.$refs.input.focus()
            }
        }
    }
</script>
