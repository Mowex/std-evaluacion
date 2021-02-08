<template>
    <div>
        <section class="section">
            <main>
                <div v-if="!loggedIn">
                    <router-view ></router-view>
                </div>
                <div v-if="loggedIn">
                    <Dashboard />
                </div>
            </main>
        </section>
    </div>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
    computed: {
        ...mapGetters([
            'loggedIn'
        ])
    }
};
</script>
