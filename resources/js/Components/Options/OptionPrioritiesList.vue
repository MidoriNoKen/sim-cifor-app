<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { usePage } from "@inertiajs/vue3";

const priorities = usePage().props.priorities.map((priority, index) => ({
    id: index,
    name: priority,
}));
const props = defineProps(["form"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="priority" value="Priority" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="priority"
            v-model="form.priority"
        >
            <option value="null" disabled>Select Priority</option>
            <option
                v-for="priority in priorities"
                :value="priority.name"
                :key="priority.id"
            >
                {{ priority.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.priority" />
    </div>
</template>
