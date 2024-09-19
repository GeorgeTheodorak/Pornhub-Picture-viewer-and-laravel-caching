<template>
    <AuthenticatedLayout>
        <div>
            <h1 class="title">Pornstars List</h1>

            <!-- Search Form and Limit Selector -->
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
            </div>

            <!-- Pornstars Table -->
            <table class="pornstar-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Hair Color</th>
                    <th>Ethnicity</th>
                    <th>Age</th>
                    <th>Pornhub ID</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="pornstar in pornstars.data" :key="pornstar.id">
                    <td>{{ pornstar.name }}</td>
                    <td>{{ pornstar.hair_color }}</td>
                    <td>{{ pornstar.ethnicity }}</td>
                    <td>{{ pornstar.age }}</td>
                    <td>{{ pornstar.pornhub_id }}</td>
                    <td><a :href="`/pornstars/${pornstar.id}`" class="details-link">View Details</a></td>
                </tr>
                </tbody>
            </table>

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
    },
    data() {
        return {
            searchQuery: this.search || "",
            localLimit: this.limit || 10,
        };
    },
    methods: {
        submitSearch() {
            Inertia.get(route('pornstars.index'), {
                search: this.searchQuery,
                limit: this.localLimit,
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
}<style scoped>
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

/* Limit Dropdown */
.limit-selector {
    display: flex;
    align-items: center;
    color: white; /* Ensure label text is visible */
}

.limit-selector label {
    color: white; /* Label text color */
    margin-right: 10px; /* Space between label and dropdown */
}

.limit-dropdown {
    padding: 8px;
    border-radius: 4px;
    border: 1px solid #ccc; /* Border color for dropdown */
    background-color: #333; /* Background color for dropdown */
    color: white; /* Text color inside dropdown */
}

.limit-dropdown option {
    background-color: #333; /* Background color for options */
    color: white; /* Text color for options */
}

/* Table Layout */
.pornstar-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background-color: #222; /* Dark background for the table */
}

.pornstar-table th,
.pornstar-table td {
    border: 1px solid #444; /* Slightly lighter border */
    padding: 8px;
    text-align: left;
    color: #f9f9f9; /* Light text color */
}

.pornstar-table th {
    background-color: #333; /* Darker background for header */
}

.details-link {
    color: #1e90ff; /* Light blue for links */
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
