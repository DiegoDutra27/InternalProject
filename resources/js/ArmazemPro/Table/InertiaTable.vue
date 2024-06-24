<template>
    <div class="m-5">
        <div class="relative" v-if="(form && enableSearch) || filters && filters.length">
            <div v-if="form && enableSearch" class=" items-center grid grid-cols-12 gap-4 ">
                <search-field
                    v-if="searchField"
                    v-model="form.q"
                    v-bind:class="{'col-span-11': filters && filters.length, 'col-span-12': !filters || !filters.length}"
                    class="w-full " @reset="reset"/>
            </div>
            <div v-if="filters && queryString.q"  class="flex flex-wrap col-span-12 my-1">
                <div class=" mr-5 whitespace-no-wrap text-gray-500 text-sm px-2 py-1 text-center rounded-full max-w-max bg-gray-200 flex items-center justify-between mt-2"
                     style="width: max-content;">
                    <span>
                     Buscar : <b>{{ queryString.q }}</b>
                    </span>
                    <font-awesome-icon icon="fas fa-times-circle" class="ml-2 cursor-pointer" @click="removeFilter('q')"></font-awesome-icon>
                </div>
            </div>
        </div>

        <div v-if="!disableTable" :class="'bg-white overflow-x-auto'">
            <table v-bind:class="tableClasses" class="w-full whitespace-no-wrap ">
                <thead>
                    <tr class="font-bold text-left">
                        <th v-for="header in columns"
                            class="px-4 pt-6 py-4 whitespace-nowrap cursor-pointer"
                            v-bind:class="header.className || ''" :key="header.key" @click="sort(header)">
                            <span class="cursor-pointer" v-if="header.sortable">{{ header.text }}</span>
                            <span class="cursor-default" v-else>{{ header.text }}</span>
                            <font-awesome-icon class="pl-1 text-xs"
                            v-bind:icon="form.sort.split(':')[1] === 'asc' ? 'fa-arrow-up' : 'fa-arrow-down'"
                            v-if="form.sort && header.sortable && header.key === form.sort.split(':')[0]"/>
                        </th>
                        <th class="px-4 pt-6 py-4 cursor-pointer" v-if="iconTable"></th>
                    </tr>
                </thead>
                <tbody>
                <tr v-for="(item, idx) in data.list" :key="item.id"
                    :class="{'cursor-pointer': hasCursor(iconTable) , 'bg-gray-100 hover:bg-gray-200 focus-within:bg-gray-200': idx % 2 === 0 , 'bg-white hover:bg-gray-200 focus-within:bg-gray-200' : idx % 2 !== 0 }"
                    >
                    <td @click="$emit('item-selected', item)" v-for="(column, index) in columns" :key="index"
                        class="px-4 py-6 text-sm">
                        <slot :name="column.key" :index="idx" :item="item">{{ item[column.key] }}</slot>
                    </td>
                    <td @click="$emit('item-selected', item)" class=" w-px" v-if="iconTable" content="Ver mais" v-tippy>
                        <p class="rounded-full bg-gray-300 w-6 h-6 content-center text-center">
                            <font-awesome-icon v-bind:icon="item.icon || iconTable"/>
                        </p>
                    </td>
                </tr>
                <tr v-if="data.list.length === 0">
                    <td class="bg-gray-50 h-28 m-0 pl-6 overflow-hidden relative" v-bind:colspan="columns.length">
                        <div class="grid grid-cols-12 gap-4">
                            <div class="col-span-2">
                                <img class="align-middle text-gray-500 w-32" src="@/Imgs/NoSearchs.png"
                                     alt="empty state"/>
                            </div>
                            <div class="col-span-10 mt-8">
                                <h1 class="text-gray-500 text-xl">{{ emptyMessage }}</h1>
                                <p class="text-gray-500 text-sm">Ainda não temos interações ou requisições nesta
                                    área</p>
                            </div>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>

        <paginate v-if="!disableTable && enablePages && data.pages" :route-name="routeName" @click="paginate"
                  :pagination="data.pages"/>

    </div>
</template>

<script>
import _ from 'lodash'
import SearchField from './SearchField.vue'
import Paginate from './Paginate.vue'

export default {
    components: {
        SearchField,
        Paginate
    },
    props: {
        columns: {
            type: Array,
            default: null
        },
        data: {
            type: Object,
            default: null
        },
        enablePages: {
            type: Boolean,
            default: true
        },
        enableSearch: {
            type: Boolean,
            default: true
        },
        filters: {
            type: Boolean,
            default: null
        },
        icon: {
            type: String,
            default: null
        },
        id: {
            type: String,
            default: 'id'
        },
        params: {
            type: Object,
            default: null
        },
        routeName: String,
        tableClasses: {
            type: String,
            default: 'text-xs'
        },
        emptyMessage: {
            type: String,
            default: 'Nenhum registro encontrado'
        },
        searchField: {
            type: Boolean,
            default: true
        },
        disableTable: {
            type: Boolean,
            default: false
        }
    },
    computed:{
        hasItemSelectedListener() {
            let has = false
            if (this.$attrs && this.$attrs['item-selected']) {
                has = true
            }
            return has;
        }
    },
    data() {
        return {
            keyModal: 0,
            queryString: {},
            showModalFilter: false,
            item: null,
            form: null,
            oldForm: {},
            search: '',
            entityName: '',
            selectedAll: false,
            selectedCount: 0,
            sidebarStatus: 'fixed',
            valueParse: {},
            iconTable : this.icon,
        }
    },
    watch: {
        form: {
            handler: _.debounce(function () {
                this.updateQueryString();
                this.$forceUpdate();

                if (!this.formHasChanged()) {
                    return;
                }

                let query = _.pickBy(this.form);
                this.$inertia.visit(this.route(this.routeName, Object.keys(query).length ? query : {clear: 1}), {
                    preserveState: true
                });

            }, 700),
            deep: true
        },
    },
    created: function () {
        if (this.columns === null) {
            this.columns = Array.from(this.data.keys())
        }
        this.createFormData();
    },
    methods: {
        hasCursor(){
            if(this.existIcon() && this.hasItemSelectedListener){
                return true;
            }
            return false;

        },
        existIcon(){
            return this.iconTable !== undefined || this.iconTable !== null || this.iconTable !== '' || this.iconTable !== '-';
        },
        removeFilter(key) {

            this.form[key] = null;
            delete this.form[key];
            delete this.form.key;

            this.queryString[key] = null;
            delete this.queryString[key];
            delete this.queryString.key;

            this.oldForm[key] = null;
            delete this.oldForm[key];
            delete this.oldForm.key;

            this.$forceUpdate();

            this.updateQueryString();
            this.$inertia.visit(this.route(this.routeName, Object.keys(this.queryString).length ? this.queryString : {clear: 1}), {
                preserveState: true
            });

        },
        updateQueryString() {
            this.$forceUpdate();
            this.queryString = _.pickBy(this.form);
            this.queryString = _.omit(this.queryString, ['sort', 'page', 'clear', 'divergence', 'size']);
        },
        close() {
            this.$emit('close')
            this.filters.forEach(filter => {
                this.valueParse[filter.key] = 'carregando...';
                try {
                    this.getValue(filter)
                } catch (err) {
                }
            });
        },
        sleep(ms) {
            return new Promise(resolve => setTimeout(resolve, ms));
        },
        formHasChanged: function () {
            for (const o in this.form) {
                if ((o === 'q' || o === 'page' || o === 'sort') && this.oldForm[o] !== this.form[o]) {
                    if (o === 'q') {
                        this.form.page = 1;
                    }
                    try {
                        if (this.form.q.replace(/[^0-9]/g, '').length === 14 && this.ValidateCnpj(this.form.q)) {
                            this.form.q = this.form.q.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/, "$1.$2.$3/$4-$5");
                        }
                    } catch (err) {}
                    Object.assign(this.oldForm, this.form);
                    return true;
                }
            }
            return false;
        },
        ValidateCnpj(cnpj) {
            var sum = 0, pos = 0, i = 0, amount = 0, result = 0, numbers = 0, digits = 0;

            amount = cnpj.length - 2
            numbers = cnpj.substring(0, amount);
            digits = cnpj.substring(amount);

            pos = amount - 7;
            for (i = amount; i >= 1; i--) {
                sum += numbers.charAt(amount - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            result = sum % 11 < 2 ? 0 : 11 - sum % 11;
            if (result != digits.charAt(0))
                return false;

            amount = amount + 1;
            numbers = cnpj.substring(0, amount);

            sum = 0;
            pos = amount - 7;
            for (i = amount; i >= 1; i--) {
                sum += numbers.charAt(amount - i) * pos--;
                if (pos < 2)
                    pos = 9;
            }
            result = sum % 11 < 2 ? 0 : 11 - sum % 11;
            if (result != digits.charAt(1))
                return false;

            return true;
        },
        createFormData: function () {
            this.form = {
                ...this.params,
                ...this.$page.props.queryString
            }
            Object.assign(this.oldForm, this.form);
        },
        reset() {
            this.$inertia.visit(this.route(this.routeName, {clear: 1}), {
                preserveState: false
            });

            this.filters.forEach(filter => {
                this.valueParse[filter.key] = 'carregando...';
                try {
                    this.getValue(filter)
                } catch (err) {
                }
            });
        },
        sort(column) {
            if (!column.sortable) {
                return;
            }
            let sort = this.form.sort.split(':');
            let sortColumn = column.sortField || column.key;

            sort[1] = (sort[0] === sortColumn && sort[1] === 'asc') ? 'desc' : 'asc';
            sort[0] = sortColumn;
            this.form.sort = sort.join(':');
        },
        paginate: function (page) {
            this.form.page = page;
        },
    },
    mounted: function () {
        if (this.filters && this.filters.length > 0) {
            this.filters.forEach(filter => {
                this.valueParse[filter.key] = 'carregando...';
                try {
                    this.getValue(filter)
                } catch (err) {
                }
            });
        }

    },
}
</script>

