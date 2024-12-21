<template>
    <article class="p-2 border mb-2">
        <h3>{{ transaction.type === 'deposit' ? 'depot' : ' retrait' }}</h3>
        <div class="flex">
            <p>Montant: {{ transaction.amount }}€</p>
            <p>Le {{ date }}</p>
        </div>
    </article>
</template>

<script setup>
import { onMounted, ref } from 'vue';

    const props = defineProps({
        transaction: Object
    });

    const date = ref(null);

    onMounted(() => {
        const tDate = new Date(props.transaction.created_at).toLocaleDateString('fr-FR');
        date.value = tDate;
    });
    console.log(props.transaction);
    
</script>