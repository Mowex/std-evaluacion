<template>
    <div class="main">
        <div class="columns">
            <div class="column is-four-fifths">
                <h1 class="title">Películas</h1>
            </div>
            <div class="column">
                <a class="button is-primary">
                    <router-link to="/movies/create">Nueva película</router-link>
                </a>
            </div>
        </div>
        <div class="columns" v-if="movies.length > 0">
            <div class="column is-full">
                <div class="table-container">
                    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Publicacion</th>
                                <th>Estado</th>
                                <th>Aciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(movie, key) in movies" :key="key">
                                <td class="td1">
                                    <div class="columns is-vcentered">
                                        <div class="column is-half">
                                            <figure style="width: 100px; padding-top: 10px">
                                                <img :src="movie.image" alt="" style="object-fit:cover">
                                            </figure>
                                        </div>
                                        <div class="column">
                                            {{ movie.name }}
                                        </div>
                                    </div>
                                </td>
                                <td class="td1"> {{ movie.publication_date }} </td>
                                <td class="td1">
                                    <span v-if="movie.turn">{{ movie.turn.is_active_string }}</span>
                                    <span v-if="!movie.turn">N/A</span>
                                </td>
                                <td class="td1">
                                    <span class="icon">
                                        <router-link :to="{ name: 'movies.edit', params: { id: movie.id } }">
                                            <i class="fas fa-pencil-alt"></i>
                                        </router-link>
                                    </span>
                                    <span class="icon">
                                        <router-link :to="{ name: 'movies.assign', params: { id: movie.id } }">
                                            <i class="fas fa-bars"></i>
                                        </router-link>
                                    </span>
                                    <span class="icon" v-if="movie.turn">
                                        <i v-if="movie.turn.is_active === 0" class="fas fa-unlock"></i>
                                        <i v-if="movie.turn.is_active !== 0" class="fas fa-lock"></i>
                                    </span>
                                    <span class="icon">
                                        <a @click="confirmDelete(movie.id)">
                                            <i class="far fa-trash-alt"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="columns" v-if="movies.length <= 0">
            <div class="column is-full">
                <h1>Actualmente no existen registros de peliculas</h1>
            </div>
        </div>
    </div>
</template>

<style lang="sass">

</style>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            movies: [],
            error: null,
            loading: false,
            successMessage: null,
        };
    },
    created(){
        if (this.$route.params?.message) {
            Swal.fire(
                '¡Registro actualizado!',
                this.$route.params?.message,
                'success'
            )
        }

        this.fetchMovies();
    },
    methods: {
        fetchMovies(){
            axios.get('/api/movies').then(response => {
                const { data: {success, movies} } = response;
                if (success) {
                    this.movies = movies;
                } else {
                    this.error = 'Error al procesar la información';
                }
            })
            .catch(error => {
                this.error = error
            })
        },
        deleteMovie(id){
            axios.get(`/api/movies/${id}/delete`).then(response => {
                const { data: {success, message, movies} } = response;
                if (success) {
                    this.movies = movies;
                    this.successMessage = message;
                    Swal.fire(
                        '¡Registros actualizados!',
                        message,
                        'success'
                    )
                } else {
                    this.error = 'Error al procesar la información';
                }
            })
            .catch(error => {
                this.error = error
            })
        },
        confirmDelete(id){
            Swal.fire({
                title: '¿Esta seguro de borrar el registro?',
                text: "Si borra el elemento, no podrá recuperarlo",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar'
                }).then((result) => {
                if (result.isConfirmed) {
                    this.deleteMovie(id);
                }
            })
        }
    }
};
</script>
