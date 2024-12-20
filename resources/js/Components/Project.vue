<template>
    <article class="p-8 border border-black">
        <div class="">
            <h2>{{ data.title }}</h2>
            <p v-if="deadlineDate">Date d'écheance : {{ deadlineDate }}</p>
        </div>
        <p>{{ spared }}/{{ data.goal_amount }}</p>
        <div class="w-full h-5 bg-blue-100 overflow-hidden relative">
            <p class="text-center absolute mx-auto">{{ percent }}%</p>
            <div class="h-full bg-green-600" :style="{ width: percent + '%' }">
            </div>
        </div>
        <p v-if="dateExpired">échéance expirée</p>
        <div v-else class="">
            <input @click="toggleAddFundsModal = true" type="button" value="Ajouter des fonds" class="border p-2 bg-blue-400">
            <input @click="toggleRemoveFundsModal = true" type="button" value="Retirer des fonds" class="border p-2 bg-blue-400">
        </div>
    </article>
    
    <ModalAddFunds v-if="toggleAddFundsModal" :toggle="() => toggleAddFundsModal = false" :project="data" :sparedValue="spared"/>
    <ModalRemoveFunds v-if="toggleRemoveFundsModal" :toggle="() => toggleRemoveFundsModal = false"/>
</template>

<script setup>
    import { onMounted, ref } from 'vue';
    import ModalAddFunds from '@/Components/ModalAddFunds.vue';
    import ModalRemoveFunds from '@/Components/ModalRemoveFunds.vue';

    const props = defineProps({
        data: Object
    });

    const spared = ref(0);
    const toggleAddFundsModal = ref(false);
    const toggleRemoveFundsModal = ref(false);
    const deadlineDate = ref(null);
    const dateExpired = ref(false);
    const percent = ref(0);

    onMounted(() => {
        if (props.data.spared !== null || props.data.spared > 0) {
            spared.value = props.data.spared;
        }

        if (props.data.deadline) {
            let date = new Date(props.data.deadline);
            const now = new Date().getTime();

            deadlineDate.value = date.toLocaleDateString('fr-FR');

            if (now > date.getTime()) {
                dateExpired.value = true;
            }
        }

        percent.value = ((spared.value/props.data.goal_amount)*100).toFixed(2);        
    });
</script>