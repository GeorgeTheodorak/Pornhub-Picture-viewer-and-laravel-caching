<template>
    <AuthenticatedLayout>
        <div class="container">
            <!-- Pornstar Name -->
            <div class="header-section">
                <h1>{{ pornstar.name }}</h1>
            </div>

            <!-- Display Pornstar's basic information in a card layout -->
            <div class="info-section">
                <div class="info-card">
                    <h2>Basic Information</h2>
                    <p><strong>Hair Color:</strong> {{ pornstar.hair_color }}</p>
                    <p><strong>Ethnicity:</strong> {{ pornstar.ethnicity }}</p>
                    <p><strong>Age:</strong> {{ pornstar.age }}</p>
                    <p><strong>Pornhub ID:</strong> {{ pornstar.pornhub_id }}</p>
                    <p><strong>License:</strong> {{ pornstar.license }}</p>
                    <p><strong>WL Status:</strong> {{ pornstar.wl_status }}</p>
                    <p><strong>Aliases:</strong> {{ pornstar.aliases.join(', ') }}</p>
                </div>

                <!-- Additional Details -->
                <div class="info-card">
                    <h2>Additional Info</h2>
                    <p><strong>Breast Size:</strong> {{ pornstar.breast_size }}</p>
                    <p><strong>Gender:</strong> {{ pornstar.gender }}</p>
                    <p><strong>Orientation:</strong> {{ pornstar.orientation }}</p>
                    <p><strong>Rank:</strong> {{ pornstar.rank }}</p>
                    <p><strong>Subscriptions:</strong> {{ pornstar.subscriptions }}</p>
                </div>
            </div>

            <!-- Display Pictures in a grid layout -->
            <div class="pictures-section">
                <h2>Pictures</h2>
                <div class="pictures-grid">
                    <div v-if="pictures.pc" class="picture-card">
                        <h3>PC</h3>
                        <img :src="pictures.pc" alt="PC Image" />
                    </div>
                </div>
            </div>

            <!-- Back to list button (replaces router-link) -->
            <button @click="navigate" class="back-button">Back to List</button>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    components: { AuthenticatedLayout },
    props: {
        pornstar: Object, // Contains all pornstar data
        pictures: Object, // Contains the picture URLs (PC, mobile, tablet)
    },
    methods: {
        navigate() {
            Inertia.visit('pornstar.index');
        },
    }
};
</script>

<style scoped>
.container {
    padding: 40px;
    color: white;
    background-color: #2c2c2c;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    max-width: 1200px;
    margin: 0 auto;
}

.header-section {
    text-align: center;
    margin-bottom: 20px;
}

h1 {
    font-size: 3em;
    color: #ffcc00;
    margin-bottom: 10px;
}

.info-section {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 40px;
}

.info-card {
    background-color: #3b3b3b;
    border-radius: 8px;
    padding: 20px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    flex: 1;
    min-width: 300px;
}

h2 {
    font-size: 1.5em;
    color: #ffcc00;
    margin-bottom: 10px;
}

p {
    font-size: 1.1em;
    line-height: 1.6em;
    margin-bottom: 8px;
    color: #ddd;
}

.pictures-section {
    margin-top: 20px;
}

.pictures-grid {
    display: flex;
    justify-content: space-around;
    gap: 20px;
}

.picture-card {
    background-color: #3b3b3b;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.pictures img {
    max-width: 100%;
    height: auto;
    display: block;
    border-radius: 8px;
}

.back-link {
    display: inline-block;
    margin-top: 30px;
    color: #ffcc00;
    text-decoration: none;
    font-weight: bold;
    transition: color 0.3s;
}

.back-link:hover {
    color: #ff9900;
}
</style>
