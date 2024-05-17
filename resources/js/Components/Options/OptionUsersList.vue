<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { usePage } from "@inertiajs/vue3";

const users = usePage().props.users;
const props = defineProps(["form"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="user" value="Assigned User" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="user"
            v-model="form.assigned_user"
        >
            <option value="null" disabled>Select User</option>
            <option v-for="user in users" :value="user.id" :key="user.id">
                {{ user.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.user" />
    </div>
</template>
