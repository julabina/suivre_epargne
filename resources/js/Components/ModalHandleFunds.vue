<template>
    <div class="modal-container">
        <div class="modal">
            <input type="button" value="X" @click="toggle">
            <h3>{{ type === "deposit" ? "Ajouter" : "Retirer" }} des fonds au projet</h3>
            <p>{{ project.title }}</p>
            <p>{{ sparedValue }}/{{ project.goal_amount }}</p>
            <form @submit.prevent="submit">
                <div class="">
                    <label v-if="type === 'deposit'" for="">Combien souhaitez vous mettre de coté</label>
                    <label v-else for="">Combien souhaitez vous retirer</label>
                    <input v-model="form.amount" type="number" name="" id="" :max="max">
                    <span v-if="toggleAmountError">Vous ne pouvez pas mettre 0</span>
                </div>
                <div class="">
                    <input v-model="toggleComment" type="checkbox" name="" id="">
                    <label for="">Ajouter un commentaire</label>
                    <span v-for="(error, ind) in $v.comment.$errors" :key="'errorComment' + ind">{{ error.$message }}</span>
                </div>
                <div v-if="toggleComment" class="">
                    <textarea v-model="form.comment" name="" id=""></textarea>
                </div>
                <div class="">
                    <input type="submit" :value="type === 'deposit' ? 'Ajouter' : 'Retirer'" class="btn-primary">
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
    import { computed, onMounted, ref } from 'vue';
    import loca from '../utils/location.json';
    import { useForm, router } from '@inertiajs/vue3';
    import { useVuelidate } from '@vuelidate/core'
    import { maxStringSize } from '../utils/i18n-validators';

    const props = defineProps({
        toggle: Function,
        project: Object,
        sparedValue: Number,
        type: String
    });

    const max = ref(0);
    const toggleAmountError = ref(false);
    
    const form = useForm({
        amount: 1,
        toggleComment: false,
        comment: ""
    });

    const rules = computed(() => {
        return {
            comment: { maxStringSize },
        }
    });

    const $v = useVuelidate(rules, form);

    onMounted(() => {
        if (props.type === "deposit") {
            max.value = props.project.goal_amount - props.sparedValue;
        } else {
            max.value = props.sparedValue;
        }
    });

    const submit = async () => {
        toggleAmountError.value = false
        const result = await $v.value.$validate();

        if (result && form.amount > 0 && form.amount <= max.value) {
            router.visit(route('transaction.add', { id: props.project.id }), {
                method: "post",
                data: {
                    form,
                    type: props.type
                }
            });
        } else {
            form.amount = 1;
            toggleAmountError.value = true
        }
    };
</script>