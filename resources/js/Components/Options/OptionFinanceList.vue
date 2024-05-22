<script setup>
import { defineProps } from "vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps(["form", "finances"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="finance_id" value="Finance Staff" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="finance_id"
            v-model="form.finance_id"
            required
        >
            <option value="null" disabled>Select Finance Staff</option>
            <option
                v-for="finance in finances"
                :key="finance.id"
                :value="finance.id"
            >
                {{ finance.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.finance_id" />
    </div>
</template>
