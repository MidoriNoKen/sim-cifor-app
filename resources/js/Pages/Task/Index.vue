<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { Bootstrap5Pagination } from "laravel-vue-pagination";
import { ref } from "vue";

const { role, position } = usePage().props.auth.user;
const tasks = ref(usePage().props.tasks);
const currentPage = ref(usePage().props.tasks.current_page);
const lastPage = ref(usePage().props.tasks.last_page);
const perPage = ref(usePage().props.tasks.per_page);

const showTask = (task) => {
    router.get(`/tasks/${task.id}`, task);
};

const editTask = (task) => {
    router.get(`/tasks/${task.id}/edit`, task);
};

const deleteTask = (task) => {
    if (confirm("Are you sure you want to delete this task?")) {
        router.delete(`/tasks/${task.id}`);
    }
};

const createTask = () => {
    router.get(`/tasks/create`);
};

const getTasks = async (page = 1) => {
    router.get(
        `/tasks`,
        { page },
        {
            preserveState: true,
            replace: true,
            onSuccess: (page) => {
                tasks.value = page.props.tasks;
                currentPage.value = page.props.tasks.current_page;
                lastPage.value = page.props.tasks.last_page;
                perPage.value = page.props.tasks.per_page;
            },
        }
    );
};
</script>

<template>
    <Head title="Task" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Task List
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="table-responsive">
                        <table class="table align-middle text-center">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Assigned</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Priority</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="task in tasks.data" :key="task.id">
                                    <td>{{ task.name }}</td>
                                    <td>{{ task.assigned_user }}</td>
                                    <td>{{ task.start_date }}</td>
                                    <td>{{ task.end_date }}</td>
                                    <td>{{ task.priority }}</td>
                                    <td>{{ task.description }}</td>
                                    <td>{{ task.status }}</td>
                                    <td>
                                        <button
                                            @click="showTask(task)"
                                            class="btn btn-primary hover-background btn-sm m-1"
                                            style="color: white"
                                        >
                                            Show
                                        </button>
                                        <button
                                            @click="editTask(task)"
                                            class="btn btn-warning hover-background btn-sm m-1"
                                            style="color: white"
                                            v-if="
                                                position !== 'Junior' ||
                                                role.name !== 'Staff'
                                            "
                                        >
                                            Edit
                                        </button>
                                        <button
                                            @click="deleteTask(task)"
                                            class="btn btn-danger text-white hover-background btn-sm m-1"
                                            v-if="
                                                position !== 'Junior' ||
                                                role.name !== 'Staff'
                                            "
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <Bootstrap5Pagination
                            :data="tasks"
                            @pagination-change-page="getTasks"
                        />
                        <div class="text-center">
                            <div
                                class="mt-4 mb-4 fw-semibold"
                                v-if="tasks.data.length == null"
                            >
                                No Data Available
                            </div>
                            <button
                                @click="createTask()"
                                class="btn btn-primary hover-background btn-sm m-1"
                                style="color: white"
                            >
                                Create
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
