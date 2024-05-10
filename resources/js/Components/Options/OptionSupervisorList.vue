<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { usePage } from "@inertiajs/vue3";

const supervisors = usePage().props.supervisors;
const props = defineProps(['form']);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="supervisor" value="Supervisor" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="supervisor" v-model="form.supervisor" required>
            <option value=null disabled>Select Supervisor</option>
            <option v-for="supervisor in supervisors" :value="supervisor.name" :key="supervisor.id">
                {{ supervisor.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.supervisor" />
    </div>
</template>
