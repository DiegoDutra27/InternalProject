<template>
    <div class="mx-auto items-right flex mt-4">
        <ul v-show="(pagination.last_page > 1)"
            class="text-center bg-white mx-auto mt-2 ml-auto items-center flex">
            <li class="mr-1">
                <button
                    class="block w-full h-full text-sm hover:bg-yellow-300 hover:text-black text-base py-1 px-3 md:px-2"
                    type="button"
                    :value="1"
                    @click="goTo">
                    << Primeira
                </button>
            </li>
            <li v-for="pageNumber in pagination.links" class="mr-1">
                <button :disabled="!pageNumber.url"
                    class="block w-full h-full text-sm hover:bg-yellow-300 hover:text-black text-base py-1 px-3 md:px-2"
                    v-bind:class="{'bg-yellow-300': pagination.current === pageNumber}"
                    :key="pageNumber"
                    type="button"
                    :value="getParam(pageNumber.url)"
                    @click="goTo">
                    {{ getLabel(pageNumber.label) }}
                </button>
            </li>
            <li class="mr-1">
                <button
                    class="block w-full h-full text-sm hover:bg-yellow-300 hover:text-black text-base py-1 px-3 md:px-2"
                    type="button"
                    :value="pagination.last_page"
                    @click="goTo">
                    Última >>
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
            this.$emit('click', e);
        },
        getParam(url){
            let page = [];
            if (url) {
                page = url.split("page=");
            }
            return page[1] ?? null;
        },
        getLabel(label) {
            switch (label) {
                case "&laquo; Previous":
                    return "< Anterior";

                case "Next &raquo;":
                    return "Próxima >";

                default:
                    return label;
            }
        },
    },
    data() {
        return {};
    }
}
</script>
