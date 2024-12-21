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
    </AuthenticatedLayout>
</template>

<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import { onMounted, ref } from 'vue';
    import Transaction from '@/Components/Transaction.vue';

    const props = defineProps({
        project: Object
    });    

    const percent = ref(0);
    const spared = ref(0);
    const deadlineDate = ref(null);
    const dateExpired = ref(false);

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

    console.log(props.project);
    
</script>