<template>
<div>
    <Navbar />
        <div class="wrapper">
        <div class="">
            <Aside />
            <router-view ></router-view>
        </div>
    </div>
</div>
</template>

<script>
import Navbar from '../components/partials/Navbar.vue';
export default {
    components: { Navbar },};
</script>
