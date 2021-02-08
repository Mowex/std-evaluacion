<template>
    <aside class="sidebar">
        <nav class="menu">
            <div class="menu-category">
            <header class="category-header">Dashboard</header>
            <ul class="menu-list">
                <li>
                    <a><router-link to="/dashboard/index">Inicio</router-link></a>
                </li>
                <li>
                    <a><router-link to="/movies">Peliculas</router-link></a>
                </li>
                <li>
                    <a><router-link to="/turns">Turnos</router-link></a>
                </li>
                <li>
                    <a><router-link to="*" disabled>Administradores</router-link></a>
                </li>
                <li>
                    <a><router-link to="*" disabled>Perfil</router-link></a>
                </li>
                <li>
                    <a><router-link to="/logout">Cerrar Sesión</router-link></a>
                </li>
            </ul>
            </div>
        </nav>
    </aside>
</template>

<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
