<template>
    <AppLayout title="Dashboard">
        <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Clientes
                </h2>
                <PrimaryButton  :type="'button'" @click="this.$inertia.visit(route('customers.create'))" content="Ver mais" v-tippy>
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
                        :data="{list: customers}"
                        :params="params"
                        :columns="columns"
                        :filters="true"
                        :icon="'fa-greater-than'"
                        id="id"
                        table-classes="text-xs"
                        routeName="customers.index"
                        @item-selected="show">

                        <template #federal_document="row">
                            {{ row.item.federal_document ? $filters.federalDocument(row.item.federal_document) : '-------------' }}
                        </template>
                        <template #name="row">
                            {{ row.item.name ? row.item.name : '-------------' }}
                        </template>
                        <template #email="row">
                            {{ row.item.email ? row.item.email : '-------------' }}
                        </template>
                        <template #phone="row">
                            {{ row.item.phone ? $filters.phone(row.item.phone) : '-------------' }}
                        </template>
                        <template #create="row">
                            {{ row.item.create ? $filters.dateFormat(row.item.create) : '-------------' }}
                        </template>
                        <template #is_active="row">
                            <font-awesome-icon class="ml-1 rounded-full fa-2xl" :icon="getActiveData(row.item.is_active).icon" :class="getActiveData(row.item.is_active).bg" v-tippy :content="getActiveData(row.item.is_active).name"></font-awesome-icon>
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
    props: ['customers'],
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
                {key: 'federal_document', text: 'CNPJ', className: 'text-left', sortable: true},
                {key: 'name', text: 'Nome', className: 'text-left', sortable: true},
                {key: 'email', text: 'E-mail', className: 'text-left', sortable: true},
                {key: 'phone', text: 'Telefone', className: 'text-left', sortable: true},
                {key: 'create', text: 'Criado', className: 'text-left', sortable: true},
                {key: 'is_active', text: 'Status', className: 'text-left', sortable: true}
            ],
        }
    },

    methods:{
        show: function (item) {
            this.$inertia.get(this.route('customers.show', item.id));
        },
        getActiveData(status) {
            switch (status) {
                case 1:
                    return {
                        bg: "bg-green-100 text-green-400",
                        icon: "far fa-check-circle",
                        name: "Ativo"
                    };
                case 0:
                    return {
                        bg: "bg-red-100 text-red-400",
                        icon: "far fa-times-circle",
                        name: "Inativo"
                    };
                default:
                    return {
                        bg: "bg-gray-100 text-gray-400",
                        icon: "fas fa-question-circle",
                        name: "Não definido"
                    };
            }
        },
    }
}

</script>
