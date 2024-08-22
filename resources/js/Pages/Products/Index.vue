<template>
    <AppLayout title="Dashboard">
        <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Produtos
                </h2>
                <PrimaryButton :type="'button'" @click="this.$inertia.visit(route('products.create'))" content="Ver mais" v-tippy>
                    Novo produto +
                </PrimaryButton>
        </template>

        <div class="py-12">
            <div v-if="$page.props.flash.message">
                <pro-flash :timeout="2500"/>
            </div>
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg m-6">
                    <pro-inertia-table
                        :data="{list: products.data, pages: products}"
                        :params="params"
                        :columns="columns"
                        :filters="true"
                        :icon="'fa-greater-than'"
                        id="id"
                        table-classes="text-xs"
                        routeName="products.index"
                        @item-selected="show">

                        <template #name="row">
                            {{ row.item.name ? row.item.name : '-------------' }}
                        </template>
                        <template #customer_id="row">
                            {{ row.item.customer_id ? row.item.customer_id : '-------------' }}
                        </template>
                        <template #brand="row">
                            {{ row.item.brand ? row.item.brand : '-------------' }}
                        </template>
                        <template #quantity="row">
                            {{ row.item.quantity ? row.item.quantity : '-------------' }}
                        </template>
                        <template #weight="row">
                            {{ row.item.weight ? row.item.weight : '-------------' }}
                        </template>
                        <template #weight_unit="row">
                            {{ row.item.weight_unit ? row.item.weight_unit : '-------------' }}
                        </template>
                        <template #price="row">
                            {{ row.item.price ? $filters.priceFormat(row.item.price) : '-------------' }}
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


export default {
    props: ['products'],
    components: {
        AppLayout,
        PrimaryButton,
        ProFlash,
        ProInertiaTable
    },
    data() {
        return{
            params: {
                sort: 'name:asc',
                page: 1,
                ...this.$page.props.queryString
            },
            columns: [
                {key: 'name', text: 'Nome', className: 'text-left', sortable: true},
                {key: 'customer_id', text: 'Empresa', className: 'text-left', sortable: true},
                {key: 'brand', text: 'Marca', className: 'text-left', sortable: true},
                {key: 'quantity', text: 'Quantidade', className: 'text-left', sortable: true},
                {key: 'weight', text: 'Peso', className: 'text-left', sortable: true},
                {key: 'weight_unit', text: 'Unidade de Peso', className: 'text-left', sortable: false},
                {key: 'price', text: 'Preço', className: 'text-left', sortable: true},
                {key: 'create', text: 'Criado', className: 'text-left', sortable: true}
            ],
        }
    },

    methods:{
        show: function (item) {
            this.$inertia.get(this.route('products.show', item.id));
        },
    }
}

</script>
