<template>
    <div class="mx-auto items-right flex mt-4">
        <ul v-show="(pagination.pageCount > 1)"
            class="text-center bg-white mx-auto mt-2 ml-auto items-center flex">
            <li class="mr-1">
                <button
                    class="block w-full h-full text-sm hover:bg-yellow-300 hover:text-black text-base py-1 px-3 md:px-2"
                    type="button"
                    :value="pagination.first"
                    @click="goTo">
                    Primeira
                </button>
            </li>
            <li class="mr-1">
                <button
                    class="block w-full h-full text-sm hover:bg-yellow-300 hover:text-black text-base py-1 px-3 md:px-2"
                    type="button"
                    :value="pagination.previous"
                    @click="goTo">
                    &lt;
                </button>
            </li>
            <li v-for="pageNumber in pagination.pagesInRange" class="mr-1">
                <button
                    class="block w-full h-full text-sm hover:bg-yellow-300 hover:text-black text-base py-1 px-3 md:px-2"
                    v-bind:class="{'bg-yellow-300': pagination.current === pageNumber}"
                    :key="pageNumber"
                    type="button"
                    :value="pageNumber"
                    @click="goTo">
                    {{ pageNumber }}
                </button>
            </li>
            <li class="mr-1">
                <button
                    class="block w-full h-full text-sm hover:bg-yellow-300 hover:text-black text-base py-1 px-3 md:px-2"
                    type="button"
                    :value="pagination.next"
                    @click="goTo">
                    &gt;
                </button>
            </li>
            <li class="mr-1">
                <button
                    class="block w-full h-full text-sm hover:bg-yellow-300 hover:text-black text-base py-1 px-3 md:px-2"
                    type="button"
                    :value="pagination.last"
                    @click="goTo">
                    Última
                </button>
            </li>
        </ul>
    </div>
</template>

<script>

export default {
    props: {
        pagination: {
            type: Object,
            default: null
        },
        routeName: String,
        callback: Function
    },
    methods: {
        goTo(e) {
            this.$emit('click', e.target.value);
        }
    },
    data() {
        return {};
    }
}
</script>
