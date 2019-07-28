<template>
    <nav class="navbar navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Fire Solution Management System</a>
            </div>
            <ul class="nav nav-pills nav-fill">
                <li><router-link :to="{name:'workorder-all'}" active-class="active">Work Order</router-link></li>
                <li><router-link :to="{name:'recurring-all'}" active-class="active">Recurring Jobs</router-link></li>

                <li class="dropdown"><router-link to="#" class="dropdown-toggle" data-toggle="dropdown">Customer<span class="caret"></span></router-link>
                    <ul class="dropdown-menu">
                        <li><router-link :to="{name:'customer-all'}" active-class="active">Customers</router-link></li>
                        <li><router-link :to="{name:'all-contact'}" active-class="active">Contacts</router-link></li>
                        <li><router-link :to="{name:'all-location'}" active-class="active">Locations</router-link></li>
                    </ul>
                </li>

                <li><router-link :to="{name:'all-equipment'}" active-class="active">Equipment</router-link></li>
                <li><router-link :to="{name:'all-contract'}" active-class="active">Contract</router-link></li>
                <li><router-link :to="{name:'all-item'}" active-class="active">Item</router-link></li>

                <li><a href="calendar#/calendar-view" active-class="active">Calender</a></li>

                <li style="border: 1px solid whitesmoke; background-color: #2b669a; border-radius: 10px;" class="dropdown pull-right"><router-link to="#" class="dropdown-toggle fa fa-cog" data-toggle="dropdown"></router-link>
                    <ul class="dropdown-menu">
                        <li><router-link :to="{name:'add-user'}" active-class="active">User</router-link></li>
                        <li><router-link :to="{name:'add-taxcode'}" active-class="active">Tax Code</router-link></li>
                        <li><router-link :to="{name:'add-equipment-type'}" active-class="active">Equipment Type</router-link></li>
                        <li><router-link :to="{name:'add-contract-type'}" active-class="active">Contract Type</router-link></li>
                    </ul>
                </li>

                <li class="pull-right"><a href="#" @click="logout">Logout</a></li>
            </ul>
        </div>
    </nav>

</template>

<script>
    import axios from 'axios';
    export default {
       name: 'CustomerHeader',
        methods:{
           logout()
           {
               axios.post('logout').then(response=>{
                    console.log(response.data)
               })
           }
        }
    }
</script>

<style>

</style>