<template>
    <div>
        <h1>Pornstars List</h1>
        <ul>
            <li v-for="pornstar in pornstars" :key="pornstar.id">
                <h2>{{ pornstar.name }}</h2>
                <p>Hair Color: {{ pornstar.hair_color }}</p>
                <p>Ethnicity: {{ pornstar.ethnicity }}</p>
                <p>Age: {{ pornstar.age }}</p>
                <img :src="pornstar.thumbnails[0]?.urls[0]" alt="Thumbnail" />
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    props: {
        pornstars: Array,
    },
};
</script>

<style scoped>
/* Add any styles you need */
</style>
