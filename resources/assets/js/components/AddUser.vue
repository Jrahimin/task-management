<template>

    <div class="container">
        <fieldset>
            <legend><h3>New User</h3></legend>
            <form class="form-horizontal" @submit.prevent="addUser">

                <div class="form-group">
                    <label class="control-label col-md-2">First Name</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="user.first_name"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Last Name</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="user.last_name"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Username</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="user.username"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">E-mail</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="user.email"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Password</label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" v-model="user.password"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Confirm Password</label>
                    <div class="col-md-6">
                        <input type="password" class="form-control" v-model="user.password_confirmation"/>
                    </div>
                </div>

                <div class="form-group alert alert-danger" v-if="errors!=''">
                    <ul>
                        <li v-for="error in errors">{{ error }}</li>
                    </ul>
                </div>

                <div class="form-group" style="background-color: #83a9cc; padding: 1%; border-radius: 2%;">
                    <div class="col-md-12">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>

            </form>
        </fieldset>
    </div>


</template>

<script>
    import axios from 'axios'

    export default {
        data(){
            return{
                user:{
                    first_name: '', last_name: '', username: '', email: '', password: '', password_confirmation: '',
                },
                errors: [],
            }
        },
        methods:{
            addUser(){
                axios.post('user/add', this.user).then(response =>{
                    this.errors = response.data.message
                    /*if(this.errors==undefined){
                        this.$router.push({name:'all-item'})
                    }*/
                });
            },
        },
    }

</script>