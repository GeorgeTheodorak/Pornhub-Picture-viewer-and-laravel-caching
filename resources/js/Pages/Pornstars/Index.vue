<template>
    <AuthenticatedLayout>
        <div>
            <h1 class="title">Pornstars List</h1>

            <!-- Search Form, Limit Selector, and Ethnicity Filter -->
            <div class="filters-container">
                <!-- Search Form -->
                <form @submit.prevent="submitSearch" class="search-form">
                    <input
                        type="text"
                        v-model="searchQuery"
                        placeholder="Search by name or pornhub_id"
                        class="search-bar"
                    />
                    <button type="submit" class="search-button">Search</button>
                </form>

                <!-- Limit Selector -->
                <div class="limit-selector">
                    <label for="limit">Results per page:</label>
                    <select v-model="localLimit" @change="handleLimitChange" class="limit-dropdown">
                        <option value="10">10</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <!-- Ethnicity Filter -->
                <div class="ethnicity-selector">
                    <label for="ethnicity">Filter by ethnicity:</label>
                    <select v-model="selectedEthnicity" @change="submitSearch" class="ethnicity-dropdown">
                        <option value="">All Ethnicities</option>
                        <option v-for="ethnicity in ethnicities" :key="ethnicity" :value="ethnicity">
                            {{ ethnicity }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- Pornstars List -->
            <ul class="pornstar-list">
                <li v-for="pornstar in pornstars.data" :key="pornstar.id" class="pornstar-item">
                    <div class="pornstar-details">
                        <h2 class="pornstar-name">{{ pornstar.name }}</h2>
                        <p><strong>Hair Color:</strong> {{ pornstar.hair_color }}</p>
                        <p><strong>Ethnicity:</strong> {{ pornstar.ethnicity }}</p>
                        <p><strong>Age:</strong> {{ pornstar.age }}</p>
                        <p><strong>Pornhub ID:</strong> {{ pornstar.pornhub_id }}</p>
                    </div>
                    <a :href="`/pornstars/${pornstar.id}`" class="details-link">View Details</a>
                </li>
            </ul>

            <!-- Pagination Links -->
            <div class="pagination">
                <button
                    :disabled="!pornstars.prev_page_url"
                    @click="navigate(pornstars.prev_page_url)"
                    class="pagination-button"
                >
                    Previous
                </button>
                <button
                    :disabled="!pornstars.next_page_url"
                    @click="navigate(pornstars.next_page_url)"
                    class="pagination-button"
                >
                    Next
                </button>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Inertia } from "@inertiajs/inertia";
import { route } from "ziggy-js"; // For pagination and form submission

export default {
    components: { AuthenticatedLayout },
    props: {
        pornstars: Object,
        search: String,
        limit: Number,
        selectedEthnicity: String,
        ethnicities: Array,
    },
    data() {
        return {
            searchQuery: this.search || "",
            localLimit: this.limit || 10,
            selectedEthnicity: this.selectedEthnicity || "",
        };
    },
    methods: {
        submitSearch() {
            Inertia.get(route('pornstars.index'), {
                search: this.searchQuery,
                limit: this.localLimit,
                ethnicity: this.selectedEthnicity,
            });
        },
        handleLimitChange(event) {
            this.localLimit = parseInt(event.target.value);
            this.submitSearch();
        },
        navigate(url) {
            if (url) {
                console.log(`Navigating to: ${url}`);
                Inertia.visit(url, {
                    preserveState: true,
                    preserveScroll: true,
                });
            }
        },
    },
};
</script>

<style scoped>
/* General Page Styling */
.title {
    color: white;
    text-align: center;
    margin-bottom: 20px;
}

.filters-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.search-form {
    display: flex;
    justify-content: center;
}

.search-bar {
    padding: 8px;
    margin-right: 10px;
    border-radius: 4px;
    border: 1px solid #ccc;
    max-width: 300px;
    width: 100%;
}

.search-button {
    padding: 8px 12px;
    background-color: #007bff;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}

.search-button:hover {
    background-color: #0056b3;
}

/* Limit and Ethnicity Dropdowns */
.limit-selector, .ethnicity-selector {
    display: flex;
    align-items: center;
}

.limit-dropdown, .ethnicity-dropdown {
    margin-left: 10px;
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc;
}

/* List Layout */
.pornstar-list {
    list-style-type: none;
    padding: 0;
    margin: 0;
}

.pornstar-item {
    background-color: #333;
    padding: 15px;
    border-radius: 8px;
    color: white;
    margin-bottom: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.pornstar-details {
    flex: 1;
}

.pornstar-name {
    font-size: 1.25rem;
    margin-bottom: 5px;
}

.details-link {
    color: #007bff;
    text-decoration: none;
    font-weight: bold;
}

.details-link:hover {
    text-decoration: underline;
}

/* Pagination */
.pagination {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.pagination-button {
    background-color: #007bff;
    color: white;
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    border-radius: 4px;
}

.pagination-button:disabled {
    background-color: #6c757d;
    cursor: not-allowed;
}
</style>
