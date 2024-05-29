<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps(["form", "supervisors"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="officer_id" value="officer" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="officer_id"
            v-model="form.officer_id"
            required
        >
            <option value="null" disabled>Select officer</option>
            <option
                v-for="officer in supervisors"
                :value="officer.id"
                :key="officer.id"
            >
                {{ officer.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.officer_id" />
    </div>
</template>
