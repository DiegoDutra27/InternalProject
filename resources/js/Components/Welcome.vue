<template>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <img src="../Imgs/Logo2.png" width="48" height="48">

            <h1 class="mt-8 text-2xl font-medium text-gray-900">
                Bem-vindo ao programa interno do Armazem Pro!
            </h1>

            <p class="mt-6 text-gray-500 leading-relaxed">
                Armazem Pro oferece uma solução completa para a armazenagem e gerenciamento de produtos. Nossas instalações modernas são projetadas para garantir a segurança e organização dos seus itens, enquanto nosso avançado sistema de gerenciamento de estoque proporciona um controle preciso e eficiente.
                Combinando tecnologia de ponta e uma equipe especializada, proporcionamos uma gestão de inventário em tempo real que melhora a visibilidade e otimiza a logística. Nossa abordagem integrada não só protege seus produtos garantindo um retorno em casos de danos, mas também assegura uma movimentação ágil e uma distribuição eficaz.
            </p>
        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
            <div>
                <div class="flex items-center">
                    <font-awesome-icon icon="fas fa-user" class="ml-2"></font-awesome-icon>
                    <h2 class="ms-3 text-xl font-semibold text-gray-900">
                        <a href="https://laravel.com/docs">Usuário</a>
                    </h2>
                </div>

                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    Clique aqui para acessar o painel de gerenciamento de usuário. Aqui você pode visualizar, editar e ajustar as configurações gerais do seu usuário, como também alterar sua senha, incluindo informações de conta.
                </p>

                <p class="mt-4 text-sm">
                    <a :href="route('profile.show')" class="inline-flex items-center font-semibold text-orange-600">
                        Visualizar usuário

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 w-5 h-5 fill-orange-600">
                            <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </p>
            </div>

            <div>
                <div class="flex items-center">
                    <font-awesome-icon icon="fas fa-user-tie" class="ml-2"></font-awesome-icon>
                    <h2 class="ms-3 text-xl font-semibold text-gray-900">
                        <a href="https://laracasts.com">Clientes</a>
                    </h2>
                </div>

                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    Clique aqui para acessar o painel de gerenciamento de clientes. Nesta área, você pode visualizar, editar e ajustar as informações e configurações de cada cliente, incluindo dados de contato e e localização.
                </p>

                <p class="mt-4 text-sm">
                    <a :href="route('customers.index')" class="inline-flex items-center font-semibold text-orange-600">
                        Visualizar clientes

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 w-5 h-5 fill-orange-600">
                            <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </p>
            </div>

            <div>
                <div class="flex items-center">
                    <font-awesome-icon icon="fas fa-box" class="ml-2"></font-awesome-icon>
                    <h2 class="ms-3 text-xl font-semibold text-gray-900">
                        <a href="https://tailwindcss.com/">Produtos</a>
                    </h2>
                </div>

                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    Clique aqui para acessar o painel de gerenciamento de produtos. Nesta área, você pode criar, visualizar, editar e ajustar as informações de cada produto, incluindo descrições, preços e estoques.
                </p>

                <p class="mt-4 text-sm">
                    <a :href="route('products.index')" class="inline-flex items-center font-semibold text-orange-600">
                        Visualizar produtos

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 w-5 h-5 fill-orange-600">
                            <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </p>
            </div>

            <div>
                <div class="flex items-center">
                    <font-awesome-icon icon="fas fa-arrow-right-arrow-left" class="ml-2"></font-awesome-icon>
                    <h2 class="ms-3 text-xl font-semibold text-gray-900">
                        Movimentações
                    </h2>
                </div>

                <p class="mt-4 text-gray-500 text-sm leading-relaxed">
                    Clique aqui para acessar o painel de movimentações de produtos. Nesta seção, você pode visualizar e registrar todas as entradas e saídas de produtos, e ajustar os registros conforme necessário.
                </p>

                <p class="mt-4 text-sm">
                    <a :href="route('movements.index')" class="inline-flex items-center font-semibold text-orange-600">
                        Visualizar moviemntações

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class="ms-1 w-5 h-5 fill-orange-600">
                            <path fill-rule="evenodd" d="M5 10a.75.75 0 01.75-.75h6.638L10.23 7.29a.75.75 0 111.04-1.08l3.5 3.25a.75.75 0 010 1.08l-3.5 3.25a.75.75 0 11-1.04-1.08l2.158-1.96H5.75A.75.75 0 015 10z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </p>
            </div>
        </div>
    </div>
</template>

<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
</script>