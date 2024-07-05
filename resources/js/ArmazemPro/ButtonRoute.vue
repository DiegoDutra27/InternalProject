<template>
    <div class="bg-white text-xs rounded text-gray-800 border border-solid border-gray-800 flex w-max hover:bg-gray-200">
        <link
            class="p-2 py-2 px-6 uppercase"
            v-if="href" :href="href">
        </link>
        <button v-if="!href"
                class="py-2 px-6 uppercase"
                :disabled="disabled"
                :type="type">
            <slot></slot>
        </button>
    </div>
</template>

<script>
import { Link } from '@inertiajs/vue3'

export default {
    props: {
        href: String,
        disabled: false,
        type: {
            type: String,
            default: 'button'
        }
    },
    components: {
        Link
    }
}
</script>