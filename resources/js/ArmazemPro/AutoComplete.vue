<template>
    <div>
        <v-select label="name"
                  class="rounded-md border border-solid border-black text-gray-700"
                  :class="customClass"
                  :options="options"
                  v-model="selectedValue"
                  @update:model-value="$emit('update:modelValue', selectedValue)"
                  @search="fetchData"
                  :style="styleCustom"
        >
            <template #no-options="{ search, searching }">
                <p class="p-5" v-if="searching">Nenhum resultado encontrado para <em>{{ search }}</em>.</p>
                <p v-else style="opacity: 0.5">Procurar...</p>
            </template>
            <template #option="option">
                <div class="d-center" v-html="parseData(option)"></div>
            </template>
            <template #selected-option="option">
                <div class="d-center" :class="textClass" v-html="parseData(option)"></div>
            </template>
            <template #spinner="{ loading }">
                <i v-if="loading" class="fas fa-spinner animate-spin" />
            </template>
        </v-select>
    </div>
</template>

<script>
import _ from 'lodash';

export default {
    props: {
        styleCustom: {
            type: String,
            default: ''
        },
        customClass: {
            type: String,
            default: ''
        },
        textClass: {
            type: String,
            default: 'text-grey-900'
        },
        modelValue: [Object, Array, String, Number],
        optionsList: Array,
        endpoint: String,
        customOption: Function,
        filters: {
            type: Object,
            default: null
        },
        concatValues: {
            type: Boolean,
            default: false
        },
        autoLoad: {
            type: Boolean,
            default: false
        }
    },
    watch: {
        modelValue: function (val) {
            //console.log(val)
            this.selectedValue = val;
        }
    },
    computed: {
        parseColumnClass() {
            return `grid-cols-${this.listHeader.length}`;
        }
    },
    data() {
        return {
            selectedValue: this.modelValue,
            initialFilters: this.parseFilters(),
            options: this.optionsList || []
        }
    },
    created() {
        if (!this.endpoint || !this.autoLoad) {
            return;
        }

        fetch((this.initialFilters)
            ? `/json/${this.endpoint}?${this.initialFilters}`
            : `/json/${this.endpoint}`
        ).then(res => {
            //console.log(res.json().then(json => (this.options = json)));
            res.json().then(json => (this.options = json));
        });
    },
    methods: {
        parseFilters() {
            if (!this.filters) {
                return;
            }
            return new URLSearchParams(this.filters).toString();
        },
        focus() {
            this.$refs.input.focus()
        },
        parseData(option) {
            //console.log(option)
            try {
                return this.customOption(option);
            } catch (err) {
                //console.log(this.modelValue)
                //console.log(this.value)
                return option.name;
            }
        },
        search: _.debounce((loading, endpoint, search, vm, initialFilters) => {
            endpoint = (initialFilters)
                ? `/json/${endpoint}?q=${escape(search)}&${initialFilters}`
                : `/json/${endpoint}?q=${escape(search)}`
            fetch(endpoint).then(res => {
                res.json().then(json => {
                    const res = json.data || json;
                    //console.log(res);
                    if (vm.concatValues) {
                        res.forEach((item) => {
                            item.old_name = item.name;
                            item.name = JSON.stringify(item);
                            return item;
                        });
                    }
                    vm.options = res;
                });
                loading(false);
            });
        }, 850),
        fetchData(search, loading) {
            //console.log(search);
            if (this.optionsList) {
                return;
            }
            loading(true);
            this.search(loading, this.endpoint, search, this, this.initialFilters);
        },
    }
}
</script>
