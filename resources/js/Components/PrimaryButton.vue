<template>
    <button :type="type" :class="[colors.bg, colors.hover, colors.focus, colors.active]" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest  focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 transition ease-in-out duration-150">
        <slot />
    </button>
</template>

<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
    colors: {
        type: Object,
        default: {
            bg: 'bg-gray-800',
            hover: 'hover:bg-gray-700' ,
            focus: 'focus:bg-gray-700 ',
            active:'active:bg-gray-900'
        },
    }
});
</script>
