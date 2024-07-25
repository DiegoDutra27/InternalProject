<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Movimentos
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
                            Movimentos
                        </template>

                        <template #description>
                            <div class="pb-20">
                                    Realize movimentações de produtos dentro do estoque.
                                </div>
                                <div class="pt-20" v-if="movement.create">
                                    Criação: {{movement.create}}
                                </div>
                                <div v-if="movement.update">
                                    Última atualização: {{movement.update}}
                                </div>
                        </template>

                        <template #form>
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12">
                                    <comp-label for="product_id" value="Empresa do Produto:" />
                                    <pro-auto-complete id="product_id" class="mt-1 block w-full" :auto-load="true"
                                        :endpoint="'products'" :customOption="productView"
                                        :customClass="'mt-1 block w-full h-10 pb-10'" v-model="form.product_id" />
                                    <comp-input-error :message="form.errors.product_id" class="mt-2" />
                                </div>
                                <div class="col-span-6">
                                    <comp-label for="quantity" value="Quantidade:" />
                                    <pro-input id="quantity" class="mt-1 block w-full" v-model="form.quantity" />
                                    <comp-input-error :message="form.errors.quantity" class="mt-2" />
                                </div>
                                <div class="col-span-6">
                                    <comp-label for="movement" value="Movimento:" />
                                    <pro-auto-complete id="movement" class="mt-1 block w-full"
                                        :options-list="typeMovement" :customClass="'mt-1 block w-full h-10 pb-10'"
                                        :auto-load="true" v-model="form.movement" />
                                    <comp-input-error :message="form.errors.movement" class="mt-2" />
                                </div>
                            </div>
                        </template>
                        <template #actions>
                                <pro-button-send class="float-right" @click.native="save">
                                    {{ movement.create ? "Atualizar" : "Cadastrar" }}
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
import Modal from '@/Components/Modal.vue';
import DialogModal from '@/Components/DialogModal.vue';
import ProFlash from '@/ArmazemPro/Flash.vue';
import ProInput from '@/ArmazemPro/Input.vue';
import ProDecimal from '@/ArmazemPro/Decimal.vue';
import ProButtonSend from '@/ArmazemPro/ButtonSend.vue';
import ProButtonRoute from '@/ArmazemPro/ButtonRoute.vue';
import ProAutoComplete from '@/ArmazemPro/AutoComplete.vue';

export default {

    props: ['movement'],

    methods: {
        productView(option) {
            return `<div class="grid grid-cols-2 ">
                        <div class="col-span-2  pl-0 text-left break-all font-semibold">${option.name || ''} | ${option.customer_id || option.customer_name} | ${this.$filters.federalDocument(option.customer_federal_document) || ''}</div>
                    </div>`
        },
        save: function () {
            let endpoint = this.movement.id ? `/movements/${this.movement.id}` : '/movements';
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
            form: this.$inertia.form({
                _method: this.movement.id ? 'PUT' : 'POST',
                id: this.movement.id ?? null,
                product_id: null,
                quantity: null,
                movement: null,
                ...this.movement
            }, {
                resetOnSuccess: false,
            }),
            typeMovement: [
                {id:'entry', name:'Entrada'},
                {id:'output', name:'Saída'}
            ],
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
