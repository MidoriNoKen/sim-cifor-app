<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { usePage } from "@inertiajs/vue3";

const statuses = usePage().props.statuses.map((status, index) => ({
    id: index,
    name: status,
}));
const props = defineProps(["form"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="status" value="Status" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="status"
            v-model="form.status"
        >
            <option value="null" disabled>Select Status</option>
            <option
                v-for="status in statuses"
                :value="status.name"
                :key="status.id"
            >
                {{ status.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.status" />
    </div>
</template>
