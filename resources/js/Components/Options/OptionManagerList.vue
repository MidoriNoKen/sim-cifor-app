<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps(["form", "managers"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="manager_id" value="Manager" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="manager"
            v-model="form.manager_id"
        >
            <option value="null" disabled>Select Manager</option>
            =
            <option
                v-for="manager in managers"
                :value="manager.id"
                :key="manager.id"
            >
                {{ manager.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.manager_id" />
    </div>
</template>
