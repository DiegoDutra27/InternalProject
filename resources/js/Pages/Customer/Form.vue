<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Clientes
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
                            Cliente
                        </template>

                        <template #description>
                            Descreva todas as informações do cliente.

                            <p class="pt-12">DATA: {{customer.create}}</p>

                        </template>

                        <template #form>
                            <div class="grid grid-cols-12 gap-4">
                                <div class="col-span-12">
                                    <comp-label for="name" value="Razão social:"/>
                                    <pro-input
                                        id="name" class="mt-1 block w-full"
                                        v-model="form.name"/>
                                    <comp-input-error :message="form.errors.name" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="federal_document" value="CNPJ:"/>
                                    <pro-input
                                        id="federal_document" class="mt-1 block w-full"
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
                                    <pro-input
                                        id="phone" class="mt-1 block w-full"
                                        v-model="form.phone"/>
                                    <comp-input-error :message="form.errors.phone" class="mt-2"/>
                                </div>
                                <div class="col-span-4">
                                    <comp-label for="zip_code" value="CEP:"/>
                                    <pro-input
                                        id="zip_code" class="mt-1 block w-full"
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
        ProButtonSend
    }
}
</script>
