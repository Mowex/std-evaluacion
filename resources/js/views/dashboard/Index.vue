<template>
    <div class="main">
        <header class="is-clearfix">
            <div>
                <h2>Global STD</h2>
                <small>Dashboard Global STD</small>
            </div>
            <hr>
        </header>
        <div class="tile is-ancestor">
            <div class="tile is-parent">
            <article class="tile is-child box transparent">
                <p class="title">{{ globalData.TotalRegisters }}</p>
                <p class="subtitle">Total de registros</p>
            </article>
            </div>
            <div class="tile is-parent">
            <article class="tile is-child box">
                <p class="title">{{ globalData.movieCount }}</p>
                <p class="subtitle">Total de peliculas</p>
            </article>
            </div>
            <div class="tile is-parent">
            <article class="tile is-child box">
                <p class="title">{{ globalData.turnCount }}</p>
                <p class="subtitle">Total de turnos</p>
            </article>
            </div>

            <div class="tile is-parent">
            <article class="tile is-child box accent">
                <p class="title">{{ globalData.turnsAssigned }}</p>
                <p class="subtitle">Total turnos asignados</p>
            </article>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            error: null,
            loading: false,
            globalData: {
                movieCount: null,
                turnCount: null,
                turnsAssigned: null,
                TotalRegisters: null,
            }
        };
    },
    created() {
        this.fetchData()
    },
    methods: {
        fetchData() {
            axios.get(`/api/dashboard`).then(response => {
                const { data: {success, movieCount, turnCount, turnsAssigned} } = response;
                if (success) {
                    this.globalData.movieCount = movieCount
                    this.globalData.turnCount = turnCount
                    this.globalData.turnsAssigned = turnsAssigned
                    this.globalData.TotalRegisters = parseInt(movieCount + turnCount);
                    console.log('response', this.globalData);
                } else {
                    this.error = message
                }
            })
            .catch(error => {
                this.error = error
            })
        }
    }
}
</script>
