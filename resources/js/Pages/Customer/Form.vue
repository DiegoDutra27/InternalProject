<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Clientes
            </h2>
            <pro-button-route type="button" @click.native="redirectToBack()">
                <font-awesome-icon icon="fas fa-arrow-left mx-1"></font-awesome-icon>
                Voltar
            </pro-button-route>
        </template>

        <div class="py-12">
            <div v-if="$page.props.flash.message">
                <pro-flash :timeout="5000"/>
            </div>
            

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <comp-form-section>
                        <template #title>
                            Cliente
                        </template>

                        <template #description>
                                <div class="pb-20">
                                    Descreva todas as informações do cliente.
                                </div>
                                <div class="pt-20" v-if="customer.create">
                                    Criação: {{customer.create}}
                                </div>
                                <div v-if="customer.update">
                                    Última atualização: {{customer.update}}
                                </div>
                        </template>

                        <template #form>
                            <div class="grid grid-cols-12 gap-4">
                                <vue-toggle v-if="form.is_active !== null"
                                :height="30"
                                :width="75"
                                checkedBg="#ea580c"
                                uncheckedBg="red"
                                :checkedText="'Ativo'"
                                :uncheckedText="'Inativo'"
                                v-model="form.is_active"
                                @click="is_active = !is_active"/>

                                <div class="col-span-12">
                                    <comp-label for="name" value="Razão social:"/>
                                    <pro-input
                                        id="name" class="mt-1 block w-full"
                                        v-model="form.name"/>
                                    <comp-input-error :message="form.errors.name" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="federal_document" value="CNPJ:"/>
                                    <pro-input-mask
                                        id="federal_document" class="mt-1 block w-full"
                                        :mask="['##.###.###/####-##']"
                                        v-model="form.federal_document"/>
                                    <comp-input-error :message="form.errors.federal_document" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="email" value="E-mail:"/>
                                    <pro-input
                                        id="email" class="mt-1 block w-full"
                                        v-model="form.email"/>
                                    <comp-input-error :message="form.errors.email" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="phone" value="Telefone:"/>
                                    <pro-input-mask
                                        id="phone" class="mt-1 block w-full"
                                        :mask="['(##) ####-####', '(##) #####-####']"
                                        v-model="form.phone"/>
                                    <comp-input-error :message="form.errors.phone" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="zip_code" value="CEP:"/>
                                    <pro-input-mask
                                        id="zip_code" class="mt-1 block w-full"
                                        :mask="['#####-###']"
                                        v-model="form.zip_code"/>
                                    <comp-input-error :message="form.errors.zip_code" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="state" value="Estado:"/>
                                    <pro-input
                                        id="state" class="mt-1 block w-full"
                                        v-model="form.state"/>
                                    <comp-input-error :message="form.errors.state" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="city" value="Cidade:"/>
                                    <pro-input
                                        id="city" class="mt-1 block w-full"
                                        v-model="form.city"/>
                                    <comp-input-error :message="form.errors.city" class="mt-2"/>
                                </div>
                                <div class="col-span-12">
                                    <comp-label for="address" value="Endereço:"/>
                                    <pro-input
                                        id="address" class="mt-1 block w-full"
                                        v-model="form.address"/>
                                    <comp-input-error :message="form.errors.address" class="mt-2"/>
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
import ProInputMask from '@/ArmazemPro/InputMask.vue';
import ProButtonRoute from '@/ArmazemPro/ButtonRoute.vue';
import { VueToggles } from 'vue-toggles';

export default {

    props: ['customer'],
    
    methods:{
        save: function () {
            let endpoint = this.customer.id ? `/customers/${this.customer.id}` : '/customers';
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
                _method: this.customer.id ? 'PUT' : 'POST',
                id: this.customer.id ?? null,
                name: null,
                federal_document: null,
                email: null,
                phone: null,
                zip_code: null,
                state: null,
                city: null,
                address: null,
                is_active: null,
                ...this.customer
            }, {
                resetOnSuccess: false,
            }),
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
        ProInputMask,
        ProButtonRoute
    }
}
</script>
