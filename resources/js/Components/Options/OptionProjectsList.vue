<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { usePage } from "@inertiajs/vue3";

const projects = usePage().props.projects;
const props = defineProps(["form"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="project" value="Project" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="project"
            v-model="form.project_id"
        >
            <option value="null" disabled>Select Project</option>
            <option
                v-for="project in projects"
                :value="project.id"
                :key="project.id"
            >
                {{ project.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.project" />
    </div>
</template>
