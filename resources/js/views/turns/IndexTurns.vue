<template>
    <div class="main">
        <div class="columns">
            <div class="column is-four-fifths">
                <h1 class="title">Turnos</h1>
            </div>
            <div class="column">
                <a class="button is-primary">
                    <router-link to="/turns/create">Nueva Turno</router-link>
                </a>
            </div>
        </div>
        <div class="columns" v-if="turns.length > 0">
            <div class="column is-full">
                <div class="table-container">
                    <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
                        <thead>
                            <tr>
                                <th>Turno</th>
                                <th>Estado</th>
                                <th>Aciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(turn, key) in turns" :key="key">
                                <td class="td1">
                                    {{ turn.hour }}
                                </td>
                                <td class="td1"> {{ turn.is_active_string }} </td>
                                <td class="td1">
                                    <span class="icon">
                                        <router-link :to="{ name: 'turns.edit', params: { id: turn.id } }">
                                            <i class="fas fa-pencil-alt"></i>
                                        </router-link>
                                    </span>
                                    <span class="icon">
                                        <i v-if="turn.is_active === 0" class="fas fa-unlock"></i>
                                        <i v-if="turn.is_active !== 0" class="fas fa-lock"></i>
                                    </span>
                                    <span class="icon">
                                        <a @click="confirmDelete(turn.id)">
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
        <div class="columns" v-if="turns.length <= 0">
            <div class="column is-full">
                <h1>Actualmente no existen registros de turnos</h1>
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
            turns: [],
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
        this.fetchTurns();
    },
    methods: {
        fetchTurns(){
            axios.get('/api/turns').then(response => {
                const { data: {success, turns} } = response;
                if (success) {
                    this.turns = turns;
                } else {
                    this.error = 'Error al procesar la información';
                }
            })
            .catch(error => {
                this.error = error
            })
        },
        deleteTurn(id){
            axios.get(`/api/turns/${id}/delete`).then(response => {
                const { data: {success, message, turns} } = response;
                if (success) {
                    this.turns = turns;
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
        confirmDelete(id) {
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
                    this.deleteTurn(id);
                }
            })
        }
    }
};
</script>
