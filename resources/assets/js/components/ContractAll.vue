<template>
    <div>
        <form class="form-horizontal">
            <div class="filter-box">
                <div class="form-group">
                    <div class="col-md-12 text-right">
                        <button @click="toContract" class="btn btn-primary"><span class=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Contract</span></button>
                    </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">Search</div>
                <div class="panel-body">
                <div class="form-group">
                <div class="col-md-3">
                    <label class="control-label col-md-4">Contract Name</label>

                    <div class="col-md-8">
                        <input class="form-control" v-model="search.contract_name" @keyup="doSearch"/>
                    </div>
                </div>

                <div class="col-md-3">
                    <label class="control-label col-md-4">Customer Name</label>

                    <div class="col-md-8">
                        <input class="form-control" v-model="search.customer_name" @keyup="doSearch"/>
                    </div>
                </div>

                <div class="col-md-2">
                    <select class="form-control" v-model="search.stat" @change="doSearch">
                        <option value="">Status</option>
                        <option value="1">Active</option>
                        <option value="2">Pending</option>
                        <option value="3">Cancelled</option>
                        <option value="4">Expired</option>
                        <option value="5">Closed</option>
                    </select>
                </div>
            </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">Contract List</div>
                <div class="panel-body">
                <table class="table table-striped text-center">
                    <thead>
                    <tr>
                        <th>Contract Name</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Balance</th>
                        <th></th>
                        <th></th>
                    </tr>
                    </thead>

                    <tbody>

                    <tr v-for="contractDetail in contractDetails">
                        <td>{{ contractDetail.name  }}</td>
                        <td>{{ contractDetail.customer.name }}</td>
                        <td>{{ contractDetail.type }}</td>

                        <td v-if="contractDetail.status==1">
                            <button type="button" class="btn btn-success btn-md"
                                    @click="changeStatus(contractDetail.id, contractDetail.name)">Active</button>
                        </td>
                        <td v-if="contractDetail.status==2"><button type="button" class="btn btn-info btn-md"
                                                                     @click="changeStatus(contractDetail.id, contractDetail.name)">Pending</button></td>
                        <td v-if="contractDetail.status==3"><button type="button" class="btn btn-warning btn-md"
                                                                     @click="changeStatus(contractDetail.id, contractDetail.name)">Cancelled</button></td>
                        <td v-if="contractDetail.status==4"><button type="button" class="btn btn-danger btn-md"
                                                                     @click="changeStatus(contractDetail.id, contractDetail.name)">Expired</button></td>
                        <td v-if="contractDetail.status==5"><button type="button" class="btn btn-warning btn-md"
                                                                     @click="changeStatus(contractDetail.id, contractDetail.name)">Closed</button></td>
                        
                        <td>{{ contractDetail.start_date }}</td>
                        <td>{{ contractDetail.end_date }}</td>
                        <td v-if="contractDetail.hour_limit">{{ contractDetail.hour_limit }} Hours</td>
                        <td v-if="contractDetail.money_limit">{{ contractDetail.money_limit }}/-</td>
                        <td v-if="contractDetail.unlimited">Unlimited</td>
                        <td>
                            <router-link :to="{name:'edit-contract',params:{id:contractDetail.id}}" tag="button" class="btn btn-primary">Edit</router-link>
                        </td>
                        <td>
                            <a class="btn btn-danger" @click="isDelete=true, contractId=contractDetail.id">Delete</a>
                        </td>
                    </tr>
                    </tbody>
                </table>

                    <div v-if="change" class="modal-dialog" style="position: fixed; top:2%; left: 0; right: 0;">
                        <div class="modal-content">

                            <div class="modal-body">
                                <change-status :contractId="contractId" :heading="heading"></change-status>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"
                                        @click="closeChange">Close</button>
                            </div>
                        </div>
                    </div>


                    <div v-if="isDelete" class="modal-dialog" role="document" style="position: fixed; top:2%; left: 0; right: 0;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" aria-label="Close" @click="isDelete=false"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Delete</h4>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal" @click="isDelete=false">Cancel</button>
                                <button id="btnDelete" type="button" class="btn btn-danger" @click="deleteContract(contractId)">Delete</button>
                            </div>
                        </div>
                    </div>

            </div>
            </div>
        </form>
    </div>
</template>

<script>
    import axios from 'axios';
    import ChangeStatus from './ChangeStatusContract.vue';

    export default {
        components:{
            changeStatus: ChangeStatus,
        },
        data(){
            return{
                contractDetails: [],
                contractDetailsCopy: [],
                search:{
                    contract_name: '',
                    customer_name: '',
                    stat: '',
                },
                change: false, contractId: '', heading: '',isDelete:false,contractId:''
            }
        },
        created() {
            axios.get('contract/all').then(response => {
                this.contractDetails = response.data;
                this.contractDetailsCopy = response.data;
            });
        },
        methods:{
            toContract(){
                this.$router.push({name:'add-contract'});
            },
            doSearch(){
                let that = this;
                this.contractDetails = this.contractDetailsCopy;

                if(this.search.contract_name){
                    this.contractDetails = this.contractDetails.filter(function (contract) {
                        if(contract.name.toLowerCase().includes(that.search.contract_name.toLowerCase())){
                            return contract;
                        }
                    })
                }
                if(this.search.customer_name){
                    this.contractDetails = this.contractDetails.filter(function (contract) {
                        if(contract.customer.name.toLowerCase().includes(that.search.customer_name.toLowerCase())){
                            return contract;
                        }
                    })
                }
                if(this.search.stat){
                    this.contractDetails = this.contractDetails.filter(function (contract) {
                        if(contract.status == that.search.stat){
                            return contract;
                        }
                    })
                }
            },
            changeStatus(id, name){
                this.change = true;
                this.contractId = id;
                this.heading = "#"+id+" "+name;
            },
            closeChange(){
                this.change = false;
            },
            deleteContract(contractId)
            {
                axios.get('delete-contract/'+contractId).then(response=>{
                    this.$router.go();
                })
            }


        }
    }
</script>

<style>

</style>