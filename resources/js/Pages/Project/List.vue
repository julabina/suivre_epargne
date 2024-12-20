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

        <Link :href="route('project.create')">
            <button class="m-2 bg-blue-500">CREATE</button>
        </Link>

        <section>
            <Project v-for="(project, ind) in projects" :key="'project' + ind" :data="project"></Project>
        </section>
    </AuthenticatedLayout>
</template>

<script setup>
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link } from '@inertiajs/vue3';
    import Project from '@/Components/Project.vue';

    const props = defineProps({
        projects: Array
    });    
</script>