<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Produtos
            </h2>
            <pro-button-route type="button" @click.native="redirectToBack()">
                <font-awesome-icon icon="fas fa-arrow-left mx-1"></font-awesome-icon>
                Voltar
            </pro-button-route>
        </template>

        <div class="py-12">
            <div v-if="$page.props.flash.message">
                <pro-flash :timeout="2500" />
            </div>


            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <comp-form-section>
                        <template #title>
                            Produto
                        </template>

                        <template #description>
                            <div class="pb-20">
                                    Descreva todas as informações do produto.
                                </div>
                                <div class="pt-20" v-if="product.create">
                                    Criação: {{product.create}}
                                </div>
                                <div v-if="product.update">
                                    Última atualização: {{product.update}}
                                </div>
                        </template>

                        <template #form>
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12">
                                    <comp-label for="name" value="Nome do Produto:" />
                                    <pro-input id="name" class="mt-1 block w-full" v-model="form.name" />
                                    <comp-input-error :message="form.errors.name" class="mt-2" />
                                </div>
                                <div class="col-span-12">
                                    <comp-label for="customer_id" value="Empresa do Produto:" />
                                    <pro-auto-complete id="customer_id" class="mt-1 block w-full" :auto-load="true"
                                        :endpoint="'customers'" :customOption="customerView"
                                        :customClass="'mt-1 block w-full h-10 pb-10'" v-model="form.customer_id" />
                                    <comp-input-error :message="form.errors.customer_id" class="mt-2" />
                                </div>
                                <div class="col-span-8">
                                    <comp-label for="brand" value="Marca:" />
                                    <pro-input id="brand" class="mt-1 block w-full" v-model="form.brand" />
                                    <comp-input-error :message="form.errors.brand" class="mt-2" />
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="quantity" value="Quantidade:" />
                                    <pro-input id="quantity" class="mt-1 block w-full" :disabled="product.create != undefined" v-model="form.quantity" />
                                    <comp-input-error :message="form.errors.quantity" class="mt-2" />
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="weight" value="Peso:" />
                                    <pro-input id="weight" class="mt-1 block w-full" v-model="form.weight" />
                                    <comp-input-error :message="form.errors.weight" class="mt-2" />
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="weight_unit" value="Unidade de Peso:" />
                                    <pro-auto-complete id="weight_unit" class="mt-1 block w-full"
                                        :options-list="typeWeightUnit" :customClass="'mt-1 block w-full h-10 pb-10'"
                                        :auto-load="true" v-model="form.weight_unit" />
                                    <comp-input-error :message="form.errors.weight_unit" class="mt-2" />
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="price" value="Preço:" />
                                    <pro-decimal :positive=true id="price" class="mt-1 block w-full" v-model="form.price" />
                                    <comp-input-error :message="form.errors.price" class="mt-2" />
                                </div>
                                <div class="col-span-12">
                                    <comp-label for="description" value="Descrição:" />
                                    <pro-input id="description" class="mt-1 block w-full" v-model="form.description" />
                                    <comp-input-error :message="form.errors.description" class="mt-2" />
                                </div>

                            </div>
                        </template>
                        <template #actions>
                                <pro-button-send v-if="product.id" class="float-left" @click.native="showModalConfirm">
                                    <font-awesome-icon icon="fas fa-solid fa-trash mx-1"></font-awesome-icon>
                                    <p class="ml-2">Deletar</p>
                                </pro-button-send>
                                <pro-button-send class="float-right" @click.native="save">
                                    {{ product.create ? "Atualizar" : "Cadastrar" }}
                                </pro-button-send>
                        </template>
                    </comp-form-section>
                    <dialog-modal :show="deleteProduct" @close="deleteProduct=false">
                        <template #title>
                            <i class="fas fa-check mr-2"/> Confirmação
                        </template>

                        <template #content>

                            <div class="py-6 text-sm text-gray-700">
                                Atenção, você está prestes a deletar um produto e não será possível voltar atrás.
                                Não aconselhamos a fazer isso sem a permissão de um supervisor. Deseja prosseguir?
                            </div>
                        </template>

                        <template #footer>
                            <button class="uppercase text-sm px-6 py-2 ml-4 rounded mb-3"
                                    @click="deleteProduct = false">
                                Cancelar
                            </button>
                            <button
                                class="uppercase px-6 py-2 ml-4 rounded mb-3 text-sm bg-red-400 hover:bg-red-600"
                                @click.native="destroyProduct">
                                Deletar
                            </button>
                        </template>
                    </dialog-modal>
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
import Modal from '@/Components/Modal.vue';
import DialogModal from '@/Components/DialogModal.vue';
import ProFlash from '@/ArmazemPro/Flash.vue';
import ProInput from '@/ArmazemPro/Input.vue';
import ProDecimal from '@/ArmazemPro/Decimal.vue';
import ProButtonSend from '@/ArmazemPro/ButtonSend.vue';
import ProButtonRoute from '@/ArmazemPro/ButtonRoute.vue';
import ProAutoComplete from '@/ArmazemPro/AutoComplete.vue';

export default {

    props: ['product'],

    methods: {
        showModalConfirm: function () {
            this.deleteProduct = true;
        },
        customerView(option) {
                return `<div class="grid grid-cols-2 ">
                        <div class="col-span-2  pl-0 text-left break-all font-semibold">${this.$filters.federalDocument(option.federal_document) || ''} | ${option.name || ''}</div>
                        </div>`
        },
        save: function () {
            if (this.producQuantity != this.form.quantity && this.product.id != undefined) {
                this.$toast.open({
                    message: 'A quantidade não pode ser atualizada na tela de produtos após a sua criação, favor utilizar o sistema de movimentos.',
                    type: 'warning',
                    duration: 2500,
                    position: 'top-right'
                });
                this.form.quantity = this.producQuantity;
                return;
            }
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
        destroyProduct: function () {
            let endpoint = `/products/${this.product.id}`;
            this.form._method = "DELETE";
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
        redirectToBack() {
            window.history.back()
        }
    },
    data() {
        return {
            producQuantity: this.product.quantity || null,
            deleteProduct: false,
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
            typeWeightUnit: ['mg', 'g', 'kg', 't'],
        }
    },
    components: {
        AppLayout,
        CompFormSection,
        CompLabel,
        CompInputError,
        Modal,
        DialogModal,
        ProFlash,
        ProInput,
        ProDecimal,
        ProButtonSend,
        ProButtonRoute,
        ProAutoComplete
    }
}
</script>
