<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps(["form", "roles"]);
</script>

<template>
    <div class="mt-4">
        <InputLabel for="role" value="Role" />
        <select
            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
            id="role"
            v-model="form.role_id"
            required
        >
            <option value="null" disabled>Select Role</option>
            <option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.name }}
            </option>
        </select>
        <InputError class="mt-2" :message="form.errors.role_id" />
    </div>
</template>
