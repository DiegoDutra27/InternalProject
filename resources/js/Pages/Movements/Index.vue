<template>
    <AppLayout title="Dashboard">
        <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Clientes
                </h2>
                <PrimaryButton  :type="'button'" @click="this.$inertia.visit(route('movements.create'))" content="Ver mais" v-tippy>
                    Novo cliente +
                </PrimaryButton>
        </template>

        <div class="py-12">
            <div v-if="$page.props.flash.message">
                <pro-flash :timeout="5000"/>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg m-6">
                    <pro-inertia-table
                        :data="{list: movements}"
                        :params="params"
                        :columns="columns"
                        :filters="true"
                        :icon="'fa-greater-than'"
                        id="id"
                        table-classes="text-xs"
                        routeName="movements.index"
                        @item-selected="show">

                        <template #customer="row">
                            {{ row.item.customer_name ? row.item.customer_name : '-------------' }}
                        </template>
                        <template #product="row">
                            {{ row.item.product_name ? row.item.product_name : '-------------' }}
                        </template>
                        <template #name="row">
                            {{ row.item.quantity ? row.item.quantity : '-------------' }}
                        </template>
                        <template #movement="row">
                            {{ row.item.movement ? movementParse(row.item.movement) : '-------------' }}
                        </template>
                        <template #create="row">
                            {{ row.item.create ? $filters.dateFormat(row.item.create) : '-------------' }}
                        </template>
                    </pro-inertia-table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ProFlash from '@/ArmazemPro/Flash.vue';
import ProInertiaTable from '@/ArmazemPro/Table/InertiaTable.vue';
import { tippy } from 'vue-tippy';


export default {
    props: ['movements'],
    components: {
        AppLayout,
        PrimaryButton,
        ProFlash,
        ProInertiaTable
    },
    data() {
        return{
            params: {
                sort: 'create:desc',
                page: 1,
                ...this.$page.props.queryString
            },
            columns: [
                {key: 'customer', text: 'Cliente', className: 'text-left', sortable: true},
                {key: 'product', text: 'Produto', className: 'text-left', sortable: true},
                {key: 'quantity', text: 'Quantidade', className: 'text-left', sortable: true},
                {key: 'movement', text: 'Movimento', className: 'text-left', sortable: true},
                {key: 'create', text: 'Criado', className: 'text-left', sortable: true},
            ],
        }
    },

    methods:{
        show: function (item) {
            this.$inertia.get(this.route('movements.show', item.id));
        },
        movementParse(movement){
            switch (movement) {
                case 'output':
                    return "Saída";
                case 'entry':
                    return 'Entrada';
                default:
                    return 'Não definido';
            }
        }
    }
}

</script>
