<template>
<div class="main">
    <div class="columns">
        <div class="column is-four-fifths">
            <h1 class="title">Películas</h1>
        </div>
    </div>
    <div class="container">
        <div class="columns box">
            <div class="column is-6" v-if="movie.name !== null">
                <h2 class="title">{{ movie.name }}</h2>
                <div style="width:180px">
                    <img :src="movie.image" alt="">
                </div>
            </div>
            <div class="column is-6">
                <h2 class="title">Asignar Turnos</h2>
                <div class="dropdown" :class="{ 'is-active': activeDropdown }">
                    <div class="dropdown-trigger">
                        <button class="button" aria-haspopup="true" aria-controls="dropdown-menu3" @click="activeDropdown = !activeDropdown">
                        <span>Turnos</span>
                        <span class="icon is-small">
                            <i class="fas fa-angle-down" aria-hidden="true"></i>
                        </span>
                        </button>
                    </div>
                    <div class="dropdown-menu" id="dropdown-menu3" role="menu">
                        <div class="dropdown-content control">
                            <a class="dropdown-item" v-for="(turn, key) in turns" :key="key">
                                <label class="radio">
                                    <input type="radio" name="turn" :id="turn.id" :value="turn.id" v-model="turnSelected">
                                    {{ turn.hour }}
                                </label>
                            </a>
                            <hr class="dropdown-divider">
                            <a class="dropdown-item" @click="cleanDropdown">Limpiar</a>
                        </div>
                    </div>&nbsp;&nbsp;
                    <div class="has-text-right">
                        <button type="submit" class="button is-primary" @click="assignTurn()">
                            <i class="far fa-save"></i>&nbsp;Guardar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            turns: [],
            movie: {
                id: null,
                name: null,
                image: null
            },
            loading: false,
            movieId: this.$route.params.id,
            error: null,
            turnSelected: null,
            activeDropdown: false
        };
    },
    created() {
        this.fetchMovie();
        this.fetchTurns();
    },
    computed: {
        isAssigned() {
            const asiggned = this.turnSelected === null ? false : true;
            return asiggned;
        }
    },
    validations: {

    },
    methods: {
        fetchTurns() {
            axios.get(`/api/turns/${this.movieId}/without-assign`).then(response => {
                const { data: {success, turns, turnsAssign} } = response;
                if (success) {
                    this.turns = turns;
                    if (turnsAssign){
                        this.turns.push(turnsAssign);
                        this.turnSelected = turnsAssign.id;
                    }
                    console.log('turnSelected:::', this.turnSelected);
                } else {
                    this.error = 'Error al procesar la información';
                }
            })
            .catch(error => {
                this.error = error
            })
        },
        fetchMovie(){
            if (this.movieId === null) {
                return;
            }

            axios.get(`/api/movies/${this.movieId}/edit`).then(response => {
                const { data: {success, movie} } = response;
                if (success) {
                    this.movie.id = movie.id;
                    this.movie.name = movie.name;
                    this.movie.image = movie.image;
                } else {
                    this.error = message
                }
            })
            .catch(error => {
                this.error = error
            })
        },
        assignTurn (){
            axios.post(`/api/movies/${this.movieId}/assign`, {
                'turn_id': this.turnSelected
            }).then(response => {
                const { data: {success, message} } = response;
                if (success) {
                    this.$router.push({
                        name: 'movies',
                        params: { message }
                    });
                } else {
                    this.error = message
                }
            })
            .catch(error => {
                this.error = error
            })
        },
        cleanDropdown() {
            this.turnSelected = null;
        }
    }
};
</script>
