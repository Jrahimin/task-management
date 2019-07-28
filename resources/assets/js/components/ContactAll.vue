<template>
    <div>
        <form class="form-horizontal">
            <div class="panel panel-info">
                <div class="panel-heading">Search</div>
                <div class="panel-body">
                <div class="form-group">
                    <div class="col-md-3">
                        <div class="col-md-10">
                            <select class="form-control" v-model="search.type" @change="doSearch">
                                <option value="name">Contact Name</option>
                                <option value="customer">Customer</option>
                                <option value="mobile">Contact No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="col-md-12">
                            <input class="form-control" v-model="search.text" @keyup="doSearch" :placeholder="placeholder"/>
                        </div>
                    </div>
                </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">Contact List</div>
                <div class="panel-body">
                    <table class="table table-striped text-center">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Customer</th>
                            <th>Primary Location</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th></th>
                        </tr>
                        </thead>

                        <tbody>

                        <tr v-for="contactDetail in contactDetails">
                            <td>{{ contactDetail.contact.name  }}</td>
                            <td>
                                {{ contactDetail.customer.name }}
                            </td>
                            <td>
                                {{ contactDetail.location.name }}
                                <p>{{ contactDetail.location.address }}
                                <p>{{ contactDetail.location.city }}</p>
                            </td>
                            <td>{{ contactDetail.contact.mobile_phone }}</td>
                            <td>{{ contactDetail.contact.email }}</td>
                            <td>
                                <router-link :to="{name:'edit-contact',params:{id:contactDetail.contact.id}}" tag="button" class="btn btn-primary">Edit</router-link>

                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';

    export default {
        data(){
            return{
                contactDetails: [],
                contactDetailsCopy: [],
                search:{
                    type: 'name',
                    text: ''
                },
                placeholder: 'put contact name here',
            }
        },
        created() {
            axios.get('contacts/all').then(response => {
                this.contactDetails=response.data;
                this.contactDetailsCopy=response.data;
            });
        },
        methods:{
            doSearch(){
                let that = this;
                this.contactDetails = this.contactDetailsCopy;

                if(this.search.text){
                    if(this.search.type=='name'){
                        this.contactDetails = this.contactDetails.filter(function (contactDetail) {
                            if(contactDetail.contact.name.toLowerCase().includes(that.search.text.toLowerCase())){
                                return contactDetail;
                            }
                        })
                    }
                    if(this.search.type=='customer'){
                        this.contactDetails = this.contactDetails.filter(function (contactDetail) {
                            if(contactDetail.customer.name.toLowerCase().includes(that.search.text.toLowerCase())){
                                return contactDetail;
                            }
                        })
                    }
                    if(this.search.type=='mobile'){
                        this.contactDetails = this.contactDetails.filter(function (contactDetail) {
                            if(contactDetail.contact.mobile_phone.toLowerCase().includes(that.search.text.toLowerCase())){
                                return contactDetail;
                            }
                        })
                    }
                }
                if(this.search.type=='name')
                    this.placeholder = "put contact name here";
                else if(this.search.type=='customer')
                    this.placeholder = "put customer name here";
                else if(this.search.type=='mobile')
                    this.placeholder = "put contact no here";
            }
        }
    }
</script>

<style>

</style>