<script setup>
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { usePage, useForm } from "@inertiajs/vue3";

const loggedRole = usePage().props.loggedRole;

const form = useForm({
    start_date: null,
    end_date: null,
    transport_type: null,
    accomodation_detail: null,
    travel_reasons: null,
});
</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            Travel Authorisation Information
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            Create new travel authorisation.
        </p>
    </header>

    <form
        @submit.prevent="form.post(route('travelAuthorisations.store'))"
        class="mt-6 space-y-6"
    >
        <div class="mt-4">
            <InputLabel for="transport_type" value="Transport Type" />

            <select
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                id="transport_type"
                v-model="form.transport_type"
                required
            >
                <option value="" disabled>Select Transport Type</option>
                <option value="Plane">Plane</option>
                <option value="Train">Train</option>
                <option value="Car">Car</option>
            </select>

            <InputError class="mt-2" :message="form.errors.transport_type" />
        </div>
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
            <InputError class="mt-2" :message="form.errors.end_date" />
        </div>
        <div class="mt-4">
            <InputLabel
                for="accomodation_detail"
                value="Accommodation Detail"
            />

            <textarea
                id="accomodation_detail"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.accomodation_detail"
            ></textarea>
            <InputError
                class="mt-2"
                :message="form.errors.accomodation_detail"
            />
        </div>
        <div class="mt-4">
            <InputLabel for="travel_reasons" value="Travel Reasons" />

            <textarea
                id="travel_reasons"
                class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                v-model="form.travel_reasons"
            ></textarea>
            <InputError class="mt-2" :message="form.errors.travel_reasons" />
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