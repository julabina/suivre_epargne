<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
            class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template>

        <section>
            <div class="">
                <Link :href="route('project.modify', { 'id' : project.id })">
                    <button class="">Modifier</button>
                </Link>
                <button @click="toggleDeleteModal = true" class="">Supprimer</button>
            </div>
            <div class="flex">
                <div>
                    <h1>{{ project.title }}</h1>
                    <p>{{ project.location }}</p>
                </div>
                <div>
                    <p v-if="dateExpired">échéance expirée</p>
                    <p v-if="deadlineDate">Date d'écheance : {{ deadlineDate }}</p>
                </div>
            </div>
            <p v-if="project.description">{{ project.description }}</p>
            <div class="flex">
                <p>{{ spared }}/{{ project.goal_amount }}</p>
                <p class="">{{ percent }}%</p>
            </div>
            <div class="w-full h-5 bg-blue-100 overflow-hidden">
                <div class="h-full bg-green-600" :style="{ width: percent + '%' }">
                </div>
            </div>
        </section>
        <section>
            <h2>Liste des transactions</h2>
            <Transaction v-for="(transaction, ind) in project.transactions" :key="'transaction' + ind" :transaction="transaction"/>
        </section>

        <div v-if="toggleDeleteModal" class="modal-container">
            <div class="modal">
                <h2>Voulez vous supprimer {{ project.title }}</h2>
                <p class="text-sm text-red-600">Cette action est définitive</p>
                <div class="flex">
                    <button @click="deleteProject" class="">Oui</button>
                    <button @click="toggleDeleteModal = false" class="">Non</button>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, router } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue';
    import Transaction from '@/Components/Transaction.vue';

    const props = defineProps({
        project: Object
    });    

    const percent = ref(0);
    const spared = ref(0);
    const deadlineDate = ref(null);
    const dateExpired = ref(false);
    const toggleDeleteModal = ref(false);

    onMounted(() => {
        if (props.project.spared !== null || props.project.spared > 0) {
            spared.value = props.project.spared;
        }

        if (props.project.deadline) {
            let date = new Date(props.project.deadline);
            const now = new Date().getTime();

            deadlineDate.value = date.toLocaleDateString('fr-FR');

            if (now > date.getTime()) {
                dateExpired.value = true;
            }
        }

        percent.value = ((spared.value/props.project.goal_amount)*100).toFixed(2); 
    });

    const deleteProject = () => {
        router.visit(route('project.delete', {'id': props.project.id}), {
            method: 'delete'
        });
    }
    
</script>