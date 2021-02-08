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
                <form autocomplete="off" @submit.prevent="createTurn" method="post">
                    <div class="field">
                    <label class="label has-text-white">Turno</label>
                        <div class="control has-icons-left has-icons-right">
                            <input class="input" :class="{ 'is-danger': $v.hour.$error }" type="time" placeholder="Hora del turno" value="" v-model.trim="$v.hour.$model">
                            <span class="icon is-small is-left">
                                <i class="far fa-clock"></i>
                            </span>
                        </div>
                        <p v-if="$v.hour.$error" class="help is-danger">This email is invalid</p>
                    </div>
                    <div class="field">
                    <label class="checkbox has-text-white">
                        <input type="checkbox" v-model="isActive">
                        Activo?
                    </label>
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
            hour: null,
            isActive: false,
            error: null,
            loading: false,
            turnId: this.$route.params.id || null
        };
    },
    created() {
        this.fetchTurn()
    },
    computed: {
        isComplete () {
            const disabled = this.hour !== null ? true : false;
            return disabled;
        },
    },
    validations: {
        hour: {
            required,
            minLength: minLength(3)
        }
    },
    methods: {
        createTurn() {
            let is_active = this.isActive ? 1 : 0;
            let formData = new FormData();
            formData.append('hour', this.hour);
            formData.append('is_active', is_active);

            const route = this.turnId === null ? `/api/turns/store` : `/api/turns/${this.turnId}`;

            axios.post(route, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }).then(response => {
                const { data: {success, message} } = response;
                if (success) {
                    this.$router.push({
                        name: 'turns',
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
        fetchTurn() {
            if (this.turnId === null) {
                return;
            }

            axios.get(`/api/turns/${this.turnId}/edit`).then(response => {
                const { data: {success, turn} } = response;
                if (success) {
                    this.hour = turn.hour;
                    this.isActive = parseInt(turn.is_active) === 0 ? false : true;
                } else {
                    this.error = message
                }
            })
            .catch(error => {
                this.error = error
            })
        },
        closeError() {
            this.error = null;
        }
    }
};
</script>
