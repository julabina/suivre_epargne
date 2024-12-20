<template>
    <article class="p-8 border border-black">
        <h2>{{ data.title }}</h2>
        <p>{{ spared }}/{{ data.goal_amount }}</p>
        <div class="">
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

    onMounted(() => {
        if (props.data.spared !== null || props.data.spared > 0) {
            spared.value = props.data.spared;
        }
    });
</script>