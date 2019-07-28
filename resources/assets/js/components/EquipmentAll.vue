<template>
    <div>
        <form class="form-horizontal">
        <div class="filter-box">
            <div class="form-group">
                <div class="col-md-12 text-right">
                    <button @click="toCustomer" class="btn btn-primary"><span class=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Equipment</span></button>
                </div>
            </div>
        </div>

            <div class="panel panel-info">
                <div class="panel-heading">Search</div>
                <div class="panel-body">
            <div class="form-group">
                <div class="col-md-3">
                    <div class="col-md-12">
                        <select class="form-control" v-model="search.type" @change="doSearch">
                            <option value="name">Equipment Name</option>
                            <option value="customer">Customer</option>
                            <option value="manufacturer">Manufacturer</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <input class="form-control" v-model="search.text" @keyup="doSearch" :placeholder="placeholder"/>
                </div>

                <div class="col-md-2">
                    <input class="form-control" v-model="search.model" placeholder="put model no" @keyup="doSearch"/>
                </div>

                <div class="col-md-2">
                    <input class="form-control" v-model="search.serial" placeholder="put serial no" @keyup="doSearch"/>
                </div>
            </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">Equipment List</div>
                <div class="panel-body">
            <table class="table table-striped text-center">
            <thead>
            <tr>
                <th>Equipment Name</th>
                <th>Customer</th>
                <th>Manufacturer</th>
                <th>Model No</th>
                <th>Serial No</th>
                <th></th>
                <th></th>
            </tr>
            </thead>

            <tbody>

            <tr v-for="equipmentDetail in equipmentDetails">
                <td>{{ equipmentDetail.name  }}</td>
                <td>{{ equipmentDetail.customer.name }}</td>
                <td>{{ equipmentDetail.manufacturer }}</td>
                <td>{{ equipmentDetail.model_no }}</td>
                <td>{{ equipmentDetail.serial_no }}</td>
                <td>
                    <router-link :to="{name:'edit-equipment',params:{id:equipmentDetail.id}}" tag="button" class="btn btn-primary">Edit</router-link>
                </td>
                <td>
                    <a class="btn btn-danger" @click="isDelete=true, equipmentId=equipmentDetail.id">Delete</a>
                </td>
            </tr>
            </tbody>
        </table>
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
                            <button id="btnDelete" type="button" class="btn btn-danger" @click="deleteEquipment(equipmentId)">Delete</button>
                        </div>
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
                equipmentDetails: [],
                equipmentDetailsCopy: [],
                action:'',
                search:{
                    type: 'name',
                    text: '',
                    manufacturer: '',
                    model: '',
                    serial: '',
                },
                isDelete:false,
                equipmentId:'',
                placeholder: 'put equipment name here',
            }
        },
        created() {
            axios.get('equipment/all').then(response => {
                this.equipmentDetails = response.data;
                this.equipmentDetailsCopy = response.data;
            });
        },
        methods:{
            toCustomer(){
                this.$router.push({name:'add-equipment'});
            },
            doSearch(){
                let that = this;
                this.equipmentDetails = this.equipmentDetailsCopy;

                if(this.search.model){
                    this.equipmentDetails = this.equipmentDetails.filter(function (equipment) {
                        if(equipment.model_no.toLowerCase().includes(that.search.model.toLowerCase())){
                            return equipment;
                        }
                    })
                }
                if(this.search.serial){
                    this.equipmentDetails = this.equipmentDetails.filter(function (equipment) {
                        if(equipment.serial_no.toLowerCase().includes(that.search.serial.toLowerCase())){
                            return equipment;
                        }
                    })
                }

                if(this.search.text){
                    if(this.search.type=='name'){
                        this.equipmentDetails = this.equipmentDetails.filter(function (equipment) {
                            if(equipment.name.toLowerCase().includes(that.search.text.toLowerCase())){
                                return equipment;
                            }
                        })
                    }
                    if(this.search.type=='manufacturer'){
                        this.equipmentDetails = this.equipmentDetails.filter(function (equipment) {
                            if(equipment.manufacturer.toLowerCase().includes(that.search.text.toLowerCase())){
                                return equipment;
                            }
                        })
                    }
                    if(this.search.type=='customer'){
                        this.equipmentDetails = this.equipmentDetails.filter(function (equipment) {
                            if(equipment.customer.name.toLowerCase().includes(that.search.text.toLowerCase())){
                                return equipment;
                            }
                        })
                    }
                }

                if(this.search.type=='name')
                    this.placeholder = "put equipment name here";
                else if(this.search.type=='customer')
                    this.placeholder = "put customer name here";
                else if(this.search.type=='manufacturer')
                    this.placeholder = "put manufacturer name here";
            },

            deleteEquipment(equipmentId)
            {
                axios.get('equipment/delete/'+equipmentId).then(response=>{
                    this.$router.go();
                })
            }
        }
    }
</script>

<style>

</style>