<template>
    <div>
        <form class="form-horizontal">
        <div class="filter-box">
            <div class="form-group">
                <div class="col-md-12 text-right">
                    <button @click="toRecurringJob" class="btn btn-primary"><span class=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Recurring Job</span></button>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">Search</div>
            <div class="panel-body">
        <div class="form-group">
            <div class="col-md-2">
                <select v-model="search.type" class="form-control" @change="doSearch">
                    <option value="id">Recurring Job Id</option>
                    <option value="customer">Customer</option>
                    <option value="city">Location</option>
                </select>
            </div>

            <div class="col-md-3">
                <input class="form-control" v-model="search.text" :placeholder="placeholder" @keyup="doSearch"/>
            </div>

            <div class="col-md-2">
                <select v-model="search.stat" class="form-control" @change="doSearch">
                    <option value="">Status</option>
                    <option value="1">New</option>
                    <option value="2">Assigned</option>
                    <option value="3">Postponed</option>
                    <option value="4">Completed</option>
                    <option value="5">Closed</option>
                </select>
            </div>

        </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">Recurring Work Order List</div>
            <div class="panel-body">
            <table class="table table-striped text-center">
                <thead>
                <tr>
                    <th>No</th>
                    <th>Name</th>
                    <th>Customer</th>
                    <th>Service Location</th>
                    <th>Created</th>
                    <th>Schedule</th>
                    <th>Last Run</th>
                    <th>Status</th>
                    <th></th>

                </tr>
                </thead>

                <tbody>

                <tr v-for="recurringDetail in recurringDetails">
                    <td>{{ recurringDetail.recurring.id }}</td>
                    <td>{{ recurringDetail.recurring.description }}</td>
                    <td>{{ recurringDetail.customer.name }}</td>
                    <td>
                        {{ recurringDetail.location.name }}
                        <p>{{ recurringDetail.location.address }}
                        <p>{{ recurringDetail.location.city }}</p>
                    </td>
                    <td>{{recurringDetail.recurring.created_at}}</td>
                    <td>
                        {{recurringDetail.schedule_time}}<br/>
                        {{recurringDetail.schedule_date_start}}<br/>
                        {{recurringDetail.schedule_date_end}}
                    </td>
                    <td>{{recurringDetail.last_run}}</td>

                    <td v-if="recurringDetail.recurring.status==1"><button type="button" class="btn btn-info btn-md"
                                @click="changeStatus(recurringDetail.recurring.id, recurringDetail.recurring.description)">New</button>
                    </td>
                    <td v-if="recurringDetail.recurring.status==2"><button type="button" class="btn btn-primary btn-md"
                                                                 @click="changeStatus(recurringDetail.recurring.id, recurringDetail.recurring.description)">Assigned</button></td>
                    <td v-if="recurringDetail.recurring.status==3"><button type="button" class="btn btn-warning btn-md"
                                                                 @click="changeStatus(recurringDetail.recurring.id, recurringDetail.recurring.description)">Postponed</button></td>
                    <td v-if="recurringDetail.recurring.status==4"><button type="button" class="btn btn-success btn-md"
                                                                 @click="changeStatus(recurringDetail.recurring.id, recurringDetail.recurring.description)">Completed</button></td>
                    <td v-if="recurringDetail.recurring.status==5"><button type="button" class="btn btn-danger btn-md"
                                                                 @click="changeStatus(recurringDetail.recurring.id, recurringDetail.recurring.description)">Closed</button></td>

                    <td>
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <span class="glyphicon glyphicon-option-vertical" aria-hidden="true"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenu1">
                                <li><router-link :to="{name:'add-workorder-item',params:{id:recurringDetail.recurring.id,type:'recurring'}}">Add Item</router-link></li>
                                <li><router-link :to="{name:'recurring-edit',params:{id:recurringDetail.recurring.id}}">Edit</router-link></li>
                                <li><a @click="isDelete=true, workOrderId=recurringDetail.recurring.id">Delete</a></li>
                            </ul>
                        </div>
                    </td>

                </tr>
                </tbody>
            </table>

                <div v-if="change" class="modal-dialog" style="position: fixed; top:2%; left: 0; right: 0;">
                    <div class="modal-content">

                        <div class="modal-body">
                            <change-status :workOrderId="workOrderId" :heading="heading"></change-status>
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
                            <button id="btnDelete" type="button" class="btn btn-danger" @click="deleteWorkOrder(workOrderId)">Delete</button>
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
    import ChangeStatus from './ChangeStatusRecurringJob.vue';

    export default {
        components:{
            changeStatus: ChangeStatus,
        },
        data(){
            return{
                recurringDetails: [],
                search:{
                    type: 'id',
                    text: '',
                    stat: '',
                    assign: '',
                },
                placeholder: 'put workorder id here', change: false, isDelete: false,
                workOrderId: '', heading: '',
            }
        },
        created() {
            axios.post('recurring-details').then(response => {
                this. recurringDetails = response.data;
            });
        },
        methods:{
            toRecurringJob(){
                this.$router.push({name:'add-recurring-workorder'});
            },
            doSearch(){
                axios.post('recurring-details', this.search).then(response => {
                    this.recurringDetails = response.data;

                    if(this.search.type=='id')
                        this.placeholder = "put workorder id here";
                    else if(this.search.type=='customer')
                        this.placeholder = "put customer name here";
                    else if(this.search.type=='city')
                        this.placeholder = "put service Location name here";
                });
            },
            changeStatus(id, description){
                this.change = true;
                this.workOrderId = id;
                this.heading = "#"+id+" "+description;
            },
            closeChange(){
                this.change = false;
            },
            deleteWorkOrder(id){
                axios.get('recurring-job/delete/'+id).then(response=>{
                    this.$router.go();
                });
            }
        }
    }
</script>

<style>

</style>