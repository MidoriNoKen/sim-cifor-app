<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";

const props = defineProps(["form", "supervisors"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="supervisor_id" value="Supervisor" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="supervisor_id"
            v-model="form.supervisor_id"
            required
        >
            <option value="null" disabled>Select Supervisor</option>
            <option
                v-for="supervisor in supervisors"
                :value="supervisor.id"
                :key="supervisor.id"
            >
                {{ supervisor.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.supervisor_id" />
    </div>
</template>
