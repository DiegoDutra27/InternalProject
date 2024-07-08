<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Produtos
            </h2>
        </template>

        <div class="py-12">
            <div v-if="$page.props.flash.message">
                <pro-flash :timeout="5000"/>
            </div>
            

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <comp-form-section>
                        <template #title>
                            Produto
                        </template>

                        <template #description>
                            Descreva todas as informações do produto.

                            <p class="pt-12">DATA: {{product.create}}</p>

                        </template>

                        <template #form>
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12">
                                    <comp-label for="name" value="Nome do Produto:"/>
                                    <pro-input
                                        id="name" class="mt-1 block w-full"
                                        v-model="form.name"/>
                                    <comp-input-error :message="form.errors.name" class="mt-2"/>
                                </div>
                                <div class="col-span-12">
                                    <comp-label for="customer_id" value="Empresa do Produto:"/>
                                    <pro-auto-complete
                                        id="customer_id" class="mt-1 block w-full"
                                        :auto-load="true"
                                        :endpoint="'customers'"
                                        :customClass="'mt-1 block w-full h-10 pb-10'"
                                        v-model="form.customer_id"/>
                                    <comp-input-error :message="form.errors.customer_id" class="mt-2"/>
                                </div>
                                <div class="col-span-8">
                                    <comp-label for="brand" value="Marca:"/>
                                    <pro-input
                                        id="brand" class="mt-1 block w-full"
                                        v-model="form.brand"/>
                                    <comp-input-error :message="form.errors.brand" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="quantity" value="Quantidade:"/>
                                    <pro-input
                                        id="quantity" class="mt-1 block w-full"
                                        v-model="form.quantity"/>
                                    <comp-input-error :message="form.errors.quantity" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="weight" value="Peso:"/>
                                    <pro-input
                                        id="weight" class="mt-1 block w-full"
                                        v-model="form.weight"/>
                                    <comp-input-error :message="form.errors.weight" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="weight_unit" value="Unidade de Peso:"/>
                                    <pro-auto-complete
                                        id="weight_unit" class="mt-1 block w-full"
                                        :options-list="typeWeightUnit"
                                        :customClass="'mt-1 block w-full h-10 pb-10'"
                                        v-model="form.weight_unit"/>
                                    <comp-input-error :message="form.errors.weight_unit" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="price" value="Preço:"/>
                                    <pro-input
                                        id="price" class="mt-1 block w-full"
                                        v-model="form.price"/>
                                    <comp-input-error :message="form.errors.price" class="mt-2"/>
                                </div>
                                <div class="col-span-12">
                                    <comp-label for="description" value="Descrição:"/>
                                    <pro-input
                                        id="description" class="mt-1 block w-full"
                                        v-model="form.description"/>
                                    <comp-input-error :message="form.errors.description" class="mt-2"/>
                                </div>

                            </div>
                        </template>
                        <template #actions>
                            <pro-button-send class="ml-2"
                                   @click.native="save"
                                   >
                                   Cadastrar
                            </pro-button-send>
                        </template>
                    </comp-form-section>
                </div>
            </div>
        </div>
    </AppLayout>
</template>


<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import CompFormSection from '@/Components/FormSection.vue';
import CompLabel from '@/Components/InputLabel.vue';
import CompInputError from '@/Components/InputError.vue';
import ProFlash from '@/ArmazemPro/Flash.vue';
import ProInput from '@/ArmazemPro/Input.vue';
import ProButtonSend from '@/ArmazemPro/ButtonSend.vue';
import ProAutoComplete from '@/ArmazemPro/AutoComplete.vue';

export default {

    props: ['product'],
    
    methods:{
        save: function () {
            let endpoint = this.product.id ? `/products/${this.product.id}` : '/products';
            this.form.post(endpoint, {
                preserveScroll: true,
                onSuccess: () => {
                    this.form.processing = false
                },
                onError: () => {
                    this.form.processing = false
                }
            });
        },
    },
    data() { 
        return {
            form: this.$inertia.form({
                _method: this.product.id ? 'PUT' : 'POST',
                id: this.product.id ?? null,
                name: null,
                customer_id: null,
                brand: null,
                quantity: null,
                weight: null,
                weight_unit: null,
                price: null,
                description: null,
                ...this.product
            }, {
                resetOnSuccess: false,
            }),
            typeWeightUnit: ['mg','g','kg','t'],
        }
    },
    components: {
        AppLayout,
        CompFormSection,
        CompLabel,
        CompInputError,
        ProFlash,
        ProInput,
        ProButtonSend,
        ProAutoComplete
    }
}
</script>
