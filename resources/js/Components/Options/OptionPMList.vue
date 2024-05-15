<script setup>
import { defineProps } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps(["form", "pms"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="pm_id" value="Project Manager" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="pm_id"
            v-model="form.pm"
            required
        >
            <option value="null" disabled>Select Project Manager</option>
            <option v-for="pm in pms" :key="pm.id" :value="pm">
                {{ pm.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.pm" />
    </div>
</template>
