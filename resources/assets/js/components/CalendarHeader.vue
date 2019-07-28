<template>
    <div>
        <nav class="navbar navbar-inverse">
            <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="../public">Fire Solution Management System</a>
                </div>
            </div>
        </nav>

        <ul class="nav nav-tabs" style="background-color: #3b8ab8">
            <li style="border-right:1px solid whitesmoke;"> <router-link :to="{name:'calendar-view'}" active-class="active">Month</router-link></li>
            <li style="border-right:1px solid whitesmoke;"> <router-link :to="{name:'week-calendar'}" active-class="active">Week</router-link></li>
            <li style="border-right:1px solid whitesmoke;"> <router-link :to="{name:'day-calendar'}" active-class="active">Day</router-link></li>
        </ul>
        <br/>
    </div>
</template>
<script>
    export default {

    }
</script>