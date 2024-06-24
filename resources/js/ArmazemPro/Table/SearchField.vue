<template>
    <div class="flex w-full bg-white" >
        <div class="items-center w-full grid grid-cols-6  text-gray-700  ">
            <comp-label class="col-span-6"
                       v-bind:value="'Buscar'"/>
            <div class="flex w-full items-center col-span-6 text-center">
                <div class="relative mt-1 fill-current text-gray-500 w-full ">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 ">
                        <div class="rounded-full p-1 bg-gray-200" style="width: 30px; height: 30px;">
                            <font-awesome-icon icon="fas fa-search" />
                        </div>
                    </span>
                    <input type='text'
                           class="rounded-md w-full focus:outline-none border-2 border-gray-300 text-sm pb-2 pl-12 pr-2 py-2 "
                           :value="modelValue"
                           placeholder="Digite algo para começar ..."
                           style="FontAwesome"
                           @input="$emit('update:modelValue', $event.target.value)"
                           autocomplete="off"
                    />
                </div>
            </div>
        </div>
    </div>


</template>

<script>
import CompLabel from '@/Components/InputLabel.vue';
export default {

    components: {
        CompLabel
    },
    props: {
        modelValue: String,
        maxWidth: {
            type: Number,
            default: 300
        }
    }
}
</script>
