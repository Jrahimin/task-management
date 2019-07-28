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
                                <option value="name">Location Name</option>
                                <option value="customer">Customer</option>
                                <option value="city">City</option>
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
                <div class="panel-heading">Location List</div>
                <div class="panel-body">
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th>Location Name</th>
                <th>Customer</th>
                <th>Address</th>
                <th>City</th>
                <th>State</th>
                <th>Zip</th>
                <th>Zone</th>
                <th> </th>
            </tr>
            </thead>

            <tbody>

            <tr v-for="locationDetail in locationDetails">
                <td>{{ locationDetail.location.name  }}</td>
                <td>
                    {{ locationDetail.customer.name }}
                </td>
                <td>
                    {{ locationDetail.location.address  }}
                </td>
                <td>{{ locationDetail.location.city  }}</td>
                <td>{{ locationDetail.location.state  }}</td>
                <td>{{locationDetail.location.zip}}</td>
                <td>{{locationDetail.location.zone}}</td>
                <td>
                    <router-link :to="{name:'edit-location',params:{id:locationDetail.location.id}}" tag="button" class="btn btn-primary">Edit</router-link>
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
                locationDetails: [],
                locationDetailsCopy: [],
                search:{
                    type: 'name',
                    text: ''
                },
                placeholder: 'put location name here',
            }
        },
        created() {
            axios.get('location/all').then(response => {
                this.locationDetails=response.data;
                this.locationDetailsCopy=response.data;
            });
        },
        methods:{
            doSearch(){
                let that = this;
                this.locationDetails = this.locationDetailsCopy;

                if(this.search.text){
                    if(this.search.type=='name'){
                        this.locationDetails = this.locationDetails.filter(function (locationDetail) {
                            if(locationDetail.location.name.toLowerCase().includes(that.search.text.toLowerCase())){
                                return locationDetail;
                            }
                        })
                    }
                    if(this.search.type=='customer'){
                        this.locationDetails = this.locationDetails.filter(function (locationDetail) {
                            if(locationDetail.customer.name.toLowerCase().includes(that.search.text.toLowerCase())){
                                return locationDetail;
                            }
                        })
                    }
                    if(this.search.type=='city'){
                        this.locationDetails = this.locationDetails.filter(function (locationDetail) {
                            if(locationDetail.location.city.toLowerCase().includes(that.search.text.toLowerCase())){
                                return locationDetail;
                            }
                        })
                    }
                }
                if(this.search.type=='name')
                    this.placeholder = "put location name here";
                else if(this.search.type=='customer')
                    this.placeholder = "put customer name here";
                else if(this.search.type=='city')
                    this.placeholder = "put city here";
            }
        }
    }
</script>

<style>

</style>