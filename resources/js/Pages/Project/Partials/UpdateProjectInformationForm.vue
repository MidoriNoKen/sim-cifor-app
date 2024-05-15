<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import OptionPMList from "@/Components/Options/OptionPMList.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { useForm, usePage } from "@inertiajs/vue3";

const loggedRole = usePage().props.loggedRole;
const project = usePage().props.project;
const pms = usePage().props.pms;

const form = useForm({
    name: project.name,
    pm: project.pm,
    start_date: project.start_date,
    end_date: project.end_date,
    description: project.description,
});
</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">Project Information</h2>

        <p class="mt-1 text-sm text-gray-600">
            Create new project's information.
        </p>
    </header>

    <form
        @submit.prevent="form.post(route('projects.store'))"
        class="mt-6 space-y-6"
    >
        <div>
            <InputLabel for="name" value="Project Name" />

            <TextInput
                id="name"
                type="text"
                class="mt-1 block w-full"
                v-model="form.name"
                required
            />

            <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <OptionPMList :form="form" v-model="form.pm" :pms="pms" />

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

        <div class="mt-4">
            <InputLabel for="description" value="Description" />

            <textarea
                id="description"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.description"
            ></textarea>
            <InputError class="mt-2" :message="form.errors.description" />
        </div>

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
