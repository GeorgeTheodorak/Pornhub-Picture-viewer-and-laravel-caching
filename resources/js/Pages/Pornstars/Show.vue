<template>
    <AuthenticatedLayout>
        <div>
            <h1>{{ pornstar.name }}</h1>
            <p><strong>Hair Color:</strong> {{ pornstar.hair_color }}</p>
            <p><strong>Ethnicity:</strong> {{ pornstar.ethnicity }}</p>
            <p><strong>Age:</strong> {{ pornstar.age }}</p>
            <p><strong>Pornhub ID:</strong> {{ pornstar.pornhub_id }}</p>
            <p><strong>License:</strong> {{ pornstar.license }}</p>
            <p><strong>WL Status:</strong> {{ pornstar.wl_status }}</p>
            <p><strong>Aliases:</strong> {{ pornstar.aliases.join(', ') }}</p>

            <!-- Additional Details -->
            <h2>Additional Info</h2>
            <p><strong>Breast Size:</strong> {{ pornstar.breast_size }}</p>
            <p><strong>Gender:</strong> {{ pornstar.gender }}</p>
            <p><strong>Orientation:</strong> {{ pornstar.orientation }}</p>
            <p><strong>Rank:</strong> {{ pornstar.rank }}</p>
            <p><strong>Subscriptions:</strong> {{ pornstar.subscriptions }}</p>

            <router-link :to="{ name: 'pornstars.index' }" class="back-link">Back to List</router-link>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

export default {
    components: { AuthenticatedLayout },
    props: {
        pornstar: Object, // This object contains all the detailed information
    },
};
</script>

<style scoped>
h1, h2 {
    color: white;
}

p {
    color: white;
}

.back-link {
    color: #007bff;
    text-decoration: none;
}

.back-link:hover {
    text-decoration: underline;
}
</style>
