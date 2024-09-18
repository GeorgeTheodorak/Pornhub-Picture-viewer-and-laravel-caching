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
                    <select v-model="limit" @change="submitSearch" class="limit-dropdown">
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

            <!-- Pornstars Grid -->
            <div class="pornstar-grid">
                <div v-for="pornstar in pornstars.data" :key="pornstar.id" class="pornstar-card">
                    <h2 class="pornstar-name">{{ pornstar.name }}</h2>
                    <p><strong>Hair Color:</strong> {{ pornstar.hair_color }}</p>
                    <p><strong>Ethnicity:</strong> {{ pornstar.ethnicity }}</p>
                    <p><strong>Age:</strong> {{ pornstar.age }}</p>
                    <p><strong>Pornhub ID:</strong> {{ pornstar.pornhub_id }}</p>
                    <a :href="`/pornstars/${pornstar.id}`" class="details-link">View Details</a>
                </div>
            </div>

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
import { Inertia } from "@inertiajs/inertia"; // For pagination and form submission

export default {
    components: { AuthenticatedLayout },
    props: {
        pornstars: Object,
        search: String, // Current search term
        limit: Number, // Current limit of items per page
        selectedEthnicity: String, // Current ethnicity filter
        ethnicities: Array, // List of all ethnicities
    },
    data() {
        return {
            searchQuery: this.search || "", // Initialize search input with current search term
            limit: this.limit || 10, // Default limit of 10 results
            selectedEthnicity: this.selectedEthnicity || "", // Selected ethnicity
        };
    },
    methods: {
        submitSearch() {
            Inertia.get(route('pornstars.index'), {
                search: this.searchQuery,
                limit: this.limit, // Send the selected limit value to the server
                ethnicity: this.selectedEthnicity, // Send the selected ethnicity value
            });
        },
        navigate(url) {
            if (url) Inertia.visit(url);
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

/* Grid Layout */
.pornstar-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* Auto-fit columns based on screen size */
    gap: 20px;
    margin: 20px 0;
}

.pornstar-card {
    background-color: #333;
    padding: 20px;
    border-radius: 10px;
    color: white;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 250px;
}

.pornstar-name {
    font-size: 1.5rem;
    margin-bottom: 10px;
}

.details-link {
    color: #007bff;
    text-decoration: none;
    margin-top: auto;
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
