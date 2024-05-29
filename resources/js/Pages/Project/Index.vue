<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router, usePage } from "@inertiajs/vue3";
import { Bootstrap5Pagination } from "laravel-vue-pagination";
import { ref } from "vue";

const { role, position } = usePage().props.auth.user;
const projects = ref(usePage().props.projects);
const currentPage = ref(usePage().props.projects.current_page);
const lastPage = ref(usePage().props.projects.last_page);
const perPage = ref(usePage().props.projects.per_page);

const showProject = (project) => {
    router.get(`/projects/${project.id}`, project);
};

const editProject = (project) => {
    router.get(`/projects/${project.id}/edit`, project);
};

const deleteProject = (project) => {
    if (confirm("Are you sure you want to delete this project?")) {
        router.delete(`/projects/${project.id}`);
    }
};

const createProject = () => {
    router.get(`/projects/create`);
};

const getProjects = async (page = 1) => {
    router.get(
        `/projects`,
        { page },
        {
            preserveState: true,
            replace: true,
            onSuccess: (page) => {
                projects.value = page.props.projects;
                currentPage.value = page.props.projects.current_page;
                lastPage.value = page.props.projects.last_page;
                perPage.value = page.props.projects.per_page;
            },
        }
    );
};
</script>

<template>
    <Head title="Project" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Project List
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
                                    <th>Project HR</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="project in projects.data"
                                    :key="project.id"
                                >
                                    <td>{{ project.name }}</td>
                                    <td>{{ project.pm }}</td>
                                    <td>{{ project.start_date }}</td>
                                    <td>{{ project.end_date }}</td>
                                    <td>{{ project.description }}</td>
                                    <td>{{ project.status }}</td>
                                    <td>
                                        <button
                                            @click="showProject(project)"
                                            class="btn btn-primary hover-background btn-sm m-1"
                                            style="color: white"
                                        >
                                            Show
                                        </button>
                                        <button
                                            @click="editProject(project)"
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
                                            @click="deleteProject(project)"
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
                            :data="projects"
                            @pagination-change-page="getProjects"
                        />
                        <div class="text-center">
                            <div
                                class="mt-4 mb-4 fw-semibold"
                                v-if="projects.data.length == null"
                            >
                                No Data Available
                            </div>
                            <button
                                @click="createProject()"
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
