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

        <form @submit.prevent="onSubmit">
            <div class="">
                <label for="">Titre</label>
                <input v-model="form.title" type="text" name="" id="">
                <span v-for="(error, ind) in $v.title.$errors" :key="'errorTitle' + ind">{{ error.$message }}</span>
            </div>
            <div class="">
                <label for="">Description</label>
                <textarea v-model="form.description"></textarea>
                <span v-for="(error, ind) in $v.description.$errors" :key="'errorDescription' + ind">{{ error.$message }}</span>
            </div>
            <div class="">
                <label for="">Montant à atteindre</label>
                <input  v-model="form.amountGoal" type="number" name="" id="">
                <span v-for="(error, ind) in $v.amountGoal.$errors" :key="'errorAmountGoal' + ind">{{ error.$message }}</span>
            </div>
            <div class="">
                <label for="">Localisation</label>
                <div v-if="form.toggleCustomLocation" class="">
                    <label for="">Lieu</label>
                    <input v-model="form.customLocation" type="text" name="" id="">
                    <span v-if="toggleErrorCustom">Le champ est requis</span>
                    <span v-for="(error, ind) in $v.customLocation.$errors" :key="'errorCustomLocation' + ind">{{ error.$message }}</span>
                </div>
                <div v-else class="">
                    <select v-model="form.location" name="" id="">
                        <option v-for="(loc, ind) in loca" :key="'locaOption' + ind" :value="ind">{{loc}}</option>
                    </select>
                </div>
                <div class="">
                    <input v-model="form.toggleCustomLocation" type="checkbox" name="" id="">
                    <label for="">Personnalisé emplacement</label>
                </div>
            </div>
            <div class="">
                <input v-model="form.toggleDeadline" type="checkbox" name="" id="">
                <label for="">inclure une deadline</label>
            </div>
            <div v-if="form.toggleDeadline" class="">
                <label for=""></label>
                <input v-model="form.deadline" type="date" name="" id="" :min="formattedDate">
                <span v-if="toggleErrorDeadline">Une date est requise</span>
            </div>
            <div class="">
                <input type="submit" value="Créer" class="p-4 bg-blue-500">
            </div>
        </form>
    </AuthenticatedLayout>
</template>

<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, router, useForm } from '@inertiajs/vue3';
    import { computed, onMounted, ref } from 'vue';
    import { useVuelidate } from '@vuelidate/core'
    import { maxStringSize, required, estimateGoalAmount } from '../../utils/i18n-validators';
    import loca from '../../utils/location.json';
    
    const formattedDate = ref('');
    const toggleErrorCustom = ref(false);
    const toggleErrorDeadline = ref(false);

    const form = useForm({
        title: "",
        description: "",
        amountGoal: 100,
        location: "1",
        customLocation: "",
        toggleCustomLocation: false,
        toggleDeadline: false,
        deadline: null
    });

    const rules = computed(() => {
        return {
            title: { maxStringSize, required },
            description: { maxStringSize },
            amountGoal: { required, estimateGoalAmount },
            customLocation: { maxStringSize },
        }
    });
    
    const $v = useVuelidate(rules, form);

    onMounted(() => {
        dateForDeadline();
    });

    const dateForDeadline = () => {
        const today = new Date();
        today.setDate(today.getDate() + 1);
        
        const formatted = today.toISOString().split('T')[0];
        formattedDate.value = formatted;       
    };

    const onSubmit = async () => {
        const result = await $v.value.$validate();
        toggleErrorCustom.value = false;

        if (form.toggleCustomLocation && form.customLocation.length === 0) {
            return toggleErrorCustom.value = true;
        }
        
        if (form.toggleDeadline && form.deadline === null) {
            return toggleErrorDeadline.value = true;
        }
        
        if (result) {
            return router.visit(route('project.store'), {
                method: 'post',
                data: {
                    form: form
                }
            });
        }
    };
</script>