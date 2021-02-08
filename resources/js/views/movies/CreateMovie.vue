<template>
<div class="main">
    <div class="columns">
        <div class="column is-four-fifths">
            <h1 class="title">Películas</h1>
        </div>
    </div>
    <div class="container">
        <div class="column is-10 is-offset-1">
            <div class="box">
            <div class="notification is-danger" v-if="error !== null">
                <button class="delete" @click="closeError()"></button>
                <strong>Error:</strong> {{ error }}
            </div>
                <form autocomplete="off" @submit.prevent="createMovie" method="post">
                    <div class="field">
                    <label class="label has-text-white">Nombre</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input" :class="{ 'is-danger': $v.name.$error }" type="text" placeholder="Nombre de la película" value="" v-model.trim="$v.name.$model">
                            <span class="icon is-small is-left">
                                <i class="fas fa-video"></i>
                            </span>
                        </div>
                        <p v-if="$v.name.$error" class="help is-danger">This email is invalid</p>
                    </div>
                    <div class="field">
                    <label class="label has-text-white">Fecha de publicación</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input" :class="{ 'is-danger': $v.publicationDate.$error }" type="date" placeholder="Fecha de publicación" value="" v-model.trim="$v.publicationDate.$model">
                            <span class="icon is-small is-left">
                                <i class="far fa-calendar-alt"></i>
                            </span>
                        </div>
                        <p v-if="$v.publicationDate.$error" class="help is-danger">This email is invalid</p>
                    </div>

                    <div v-if="currentImage">
                        <img :src="currentImage" width="180" style="object-fit:cover;">
                    </div>

                    <div class="field">
                    <label class="label has-text-white">Imagen</label>
                        <div class="file is-warning">
                            <label class="file-label">
                                <input class="file-input" type="file" ref="file" id="file" name="image" v-on:change="handleFileUpload()">
                                <span class="file-cta">
                                <span class="file-icon">
                                    <i class="fas fa-upload"></i>
                                </span>
                                <span class="file-label">
                                    Primary file…
                                </span>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="has-text-right">
                        <button type="submit" class="button is-primary" :disabled='!isComplete'>
                            <i class="far fa-save"></i>&nbsp;Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import { required, minLength } from 'vuelidate/lib/validators'
import axios from 'axios'

export default {
    data() {
        return {
            name: null,
            publicationDate: null,
            image: null,
            currentImage: null,
            error: null,
            loading: false,
            movieId: this.$route.params.id || null
        };
    },
    created() {
        this.fetchMovie()
    },
    computed: {
        isComplete () {
            let disabled
            if (this.movieId === null){
                disabled = this.name && this.publicationDate && this.image ? true : false;
            } else {
                disabled = this.name && this.publicationDate ? true : false;
            }
            return disabled;
        }
    },
    validations: {
        name: {
            required,
            minLength: minLength(2)
        },
        publicationDate: {
            required,
        },
        image: {
            required,
        }
    },
    methods: {
        createMovie() {
            let formData = new FormData();
            formData.append('name', this.name);
            formData.append('publicationDate', this.publicationDate);
            formData.append('image', this.image);

            const route = this.movieId === null ? `/api/movies/store` : `/api/movies/${this.movieId}`;

            axios.post(route, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
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
        fetchMovie(){
            if (this.movieId === null) {
                return;
            }

            axios.get(`/api/movies/${this.movieId}/edit`).then(response => {
                const { data: {success, movie} } = response;
                if (success) {
                    this.name = movie.name;
                    this.publicationDate = movie.publication_date;
                    this.currentImage = movie.image;
                } else {
                    this.error = message
                }
            })
            .catch(error => {
                this.error = error
            })
        },
        handleFileUpload() {
            this.image = this.$refs.file.files[0];
        },
        closeError() {
            this.error = null;
        }
    }
};
</script>
