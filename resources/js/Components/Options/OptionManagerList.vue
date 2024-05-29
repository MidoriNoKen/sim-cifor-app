<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps(["form", "managers"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="manager_id" value="HR" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="HR"
            v-model="form.manager_id"
        >
            <option value="null" disabled>Select HR</option>
            =
            <option v-for="HR in managers" :value="HR.id" :key="HR.id">
                {{ HR.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.manager_id" />
    </div>
</template>
