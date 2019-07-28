<template>
    <div>
        <form class="form-horizontal">
        <div class="filter-box">
            <div class="form-group">
                <div class="col-md-12 text-right">
                    <button @click="toCustomer" class="btn btn-primary"><span class=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Customer</span></button>
                </div>
            </div>
        </div>

            <div class="panel panel-info">
                <div class="panel-heading">Search By</div>
                <div class="panel-body">
                    <div class="form-group">
                        <div class="col-md-2">
                            <select v-model="search.type" class="form-control" @change="doSearch">
                                <option value="name">Name</option>
                                <option value="email">Email</option>
                                <option value="mobile">Contact No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <input class="form-control" v-model="search.text" :placeholder="placeholder" @keyup="doSearch"/>
                        </div>

                        <div class="col-md-6">
                            <label class="radio-inline">
                                <input type="radio" value="1" v-model="search.stat" @change="doSearch">Active
                            </label>
                            <label class="radio-inline">
                                <input type="radio" value="0" v-model="search.stat" @change="doSearch">Not active
                            </label>
                        </div>

                    </div>
                    <div v-if="isDelete" class="modal-dialog" role="document" style="position: fixed; top:2%; left: 0; right: 0;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" aria-label="Close" @click="isDelete=false"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Delete</h4>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure want to delete? All of the work orders , contacts , contracts, locations related to this customer will be deleted</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal" @click="isDelete=false">Cancel</button>
                                <button id="btnDelete" type="button" class="btn btn-danger" @click="deleteCustomer(customerId)">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">Customer List</div>
                <div class="panel-body">
            <div class="table">
                <table class="table table-striped text-center">
                    <thead>
                    <tr>
                        <th>Customer Name</th>
                        <th>Primary Contact</th>
                        <th>Primary Location</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr v-for="customerDetail in customerDetails">

                        <td><router-link :to="{name:'customer-details',params:{id:customerDetail.customer.id}}"><span style="color: royalblue">{{ customerDetail.customer.name  }}</span></router-link></td>
                        <td>
                            {{ customerDetail.contact.name }}
                            <p>{{ customerDetail.contact.email }}
                            <p>{{ customerDetail.contact.mobile_phone }}</p>
                        </td>
                        <td>
                            {{ customerDetail.location.name }}
                            <p>{{ customerDetail.location.address }}
                            <p>{{ customerDetail.location.city }}</p>
                        </td>
                        <td>{{ customerDetail.status }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">

                                    <li><router-link :to="{name:'edit',params:{id:customerDetail.customer.id}}">Edit</router-link></li>
                                    <li><router-link :to="{name:'add-contact',params:{id:customerDetail.customer.id}}">Add Contact</router-link></li>
                                    <li><router-link :to="{name:'add-location',params:{id:customerDetail.customer.id}}">Add Location</router-link></li>
                                    <li><a  @click="isDelete=true, customerId=customerDetail.customer.id">Delete</a></li>
                                </ul>
                            </div>

                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
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
                customerDetails: [],
                action:'',
                search:{
                    type: 'name',
                    text: '',
                    stat: '',
                },
                placeholder: 'put customer name here',
                isDelete:false,
                customerId:''
            }
        },
        created() {
            axios.post('customer-details',).then(response => {
                this.customerDetails = response.data;
            });
        },
        methods:{
            toCustomer(){
                this.$router.push({name:'add-customer'});
            },
            doSearch(){
                axios.post('customer-details', this.search).then(response => {
                    this.customerDetails = response.data;

                    if(this.search.type=='name')
                        this.placeholder = "put customer name here";
                    else if(this.search.type=='email')
                        this.placeholder = "put customer email here";
                    else if(this.search.type=='mobile')
                        this.placeholder = "put customer Contact No here";
                });
            },
            deleteCustomer(customerId)
            {
                axios.get('customer/delete/'+customerId).then(response=>{
                  this.$router.go();
                })
            }
        }
    }
</script>

<style>

</style>