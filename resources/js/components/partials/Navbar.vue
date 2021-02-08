<template>
    <nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a href="#" class="navbar-item has-text-white">
                <h1><strong>Global STD</strong></h1>
            </a>
            <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbar-example">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            </a>
        </div>

        <div id="navbar-example" class="navbar-menu">
            <!-- <div class="navbar-start">
                <a class="navbar-item is-tab">Home</a>
                <a class="navbar-item is-tab ">Features</a>
                <a class="navbar-item is-tab">Documentation</a>
            </div> -->
            <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                <a class="button is-light">
                    <router-link to="/logout">Log Out</router-link>
                </a>
                </div>
            </div>
            </div>
        </div>
    </nav>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
