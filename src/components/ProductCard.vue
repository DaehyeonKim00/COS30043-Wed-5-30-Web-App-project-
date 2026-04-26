<template>
    <div class="card h-100">
        <!-- Product image -->
        <img
            :src="product.image"
            class="card-img-top"
            :alt="product.name">
            
        <!-- Product details -->
        <div class="card-body d-flex flex-column">
            <p class="text-muted mb-2">{{ product.category }}</p>
            <h5 class="card-title">{{ product.name }}</h5>
            <p class="card-text">{{ product.description }}</p>
            <p class="fw-bold mt-auto mb-3">${{ product.price }}</p>

            <!-- View Details button -->
            <router-link
                class="btn btn-dark"
                :to="'/products/' + product.id">
                View Details
            </router-link>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'ProductCard',
        props: {
            product: {
                type: Object,
                required: true
            }
        }
    }
</script>

<style scoped>
    .card-img-top {
        height: 220px;
        object-fit: cover;
    }
</style>
