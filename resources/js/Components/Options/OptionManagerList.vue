<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { usePage } from "@inertiajs/vue3";

const managers = usePage().props.managers;
const props = defineProps(["form"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="manager" value="Manager" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="manager"
            v-model="form.manager"
        >
            <option value="null" disabled>Select Manager</option>
            =
            <option
                v-for="manager in managers"
                :value="manager.name"
                :key="manager.id"
            >
                {{ manager.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.manager" />
    </div>
</template>
