<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import OptionUsersList from "@/Components/Options/OptionUsersList.vue";
import OptionProjectsList from "@/Components/Options/OptionProjectsList.vue";
import OptionPrioritiesList from "@/Components/Options/OptionPrioritiesList.vue";
import OptionStatusesList from "@/Components/Options/OptionStatusesList.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const loggedRole = usePage().props.loggedRole;
const task = usePage().props.task;
const users = usePage().props.users;
const projects = usePage().props.projects;
const priorities = usePage().props.priorities;
const statuses = usePage().props.statuses;

const form = useForm({
    name: task.name,
    project_id: task.project_id,
    assigned_user: task.assigned_user,
    start_date: task.start_date,
    end_date: task.end_date,
    priority: task.priority,
    description: task.description,
    status: task.status,
});
</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Task Information</h2>

        <p class="mt-1 text-sm text-gray-600">Create new task's information.</p>
    </header>

    <form
        @submit.prevent="form.post(route('tasks.store'))"
        class="mt-6 space-y-6"
    >
        <div>
            <InputLabel for="name" value="Task Name" />

            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
            />

            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <OptionProjectsList
            :form="form"
            v-model="form.project_id"
            :projects="projects"
        />

        <OptionUsersList
            :form="form"
            v-model="form.assigned_user"
            :users="users"
        />

        <div class="mt-4">
            <InputLabel for="start_date" value="Start Date" />

            <input
                id="start_date"
                type="date"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.start_date"
                required
            />
            <InputError class="mt-2" :message="form.errors.start_date" />
        </div>

        <div class="mt-4">
            <InputLabel for="end_date" value="End Date" />

            <input
                id="end_date"
                type="date"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.end_date"
                required
            />
            <InputError class="mt-2" :message="form.errors.start_date" />
        </div>

        <OptionPrioritiesList
            :form="form"
            v-model="form.priority"
            :priorities="priorities"
        />

        <div class="mt-4">
            <InputLabel for="description" value="Description" />

            <textarea
                id="description"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.description"
            ></textarea>
            <InputError class="mt-2" :message="form.errors.description" />
        </div>

        <OptionStatusesList
            :form="form"
            v-model="form.status"
            :statuses="statuses"
        />

        <div class="flex items-center gap-4">
            <PrimaryButton :disabled="form.processing">Save</PrimaryButton>

            <Transition
                enter-active-class="transition ease-in-out"
                enter-from-class="opacity-0"
                leave-active-class="transition ease-in-out"
                leave-to-class="opacity-0"
            >
                <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
                    Saved.
                </p>
            </Transition>
        </div>
    </form>
</template>
