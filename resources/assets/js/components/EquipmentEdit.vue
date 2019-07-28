<template>
    <div class="container">
        <fieldset>
            <legend><h3>New Equipment</h3></legend>

            <form class="form-horizontal" @submit.prevent="editEquipment">
                <div class="row">
                    <fieldset>
                        <legend>Equipment Info</legend>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-2" >Name</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="equipment.name"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2" >Type</label>

                                <div class="col-md-10">
                                    <select class="form-control" v-model.number="equipment.type">
                                        <option value="1">Type 1</option>
                                        <option value="2">Type 2</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2" >Customer</label>

                                <div class="col-md-10">
                                    <select class="form-control" v-model.number="equipment.customer_id"
                                            @change="generateElements">
                                        <option value="">Select a Customer</option>
                                        <option v-for="customer in customers" :value="customer.id" >{{customer.name}}</option>
                                    </select>
                                </div>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="form-group">
                                <label class="control-label col-md-2" >Manufacturer</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="equipment.manufacturer"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2" >Model No</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="equipment.model_no"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2" >Serial No</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="equipment.serial_no"/>
                                </div>
                            </div>

                        </div>
                    </fieldset>

                </div>

                <div class="row">
                    <fieldset>
                        <legend>Service Info</legend>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-2" >Purchase Date</label>

                                <div class="col-md-10">
                                    <input type="date" class="form-control" v-model="equipment.purchase_date"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2" >Purchased From</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="equipment.purchased_from"/>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="control-label col-md-2" >Installed On</label>

                                <div class="col-md-10">
                                    <input type="date" class="form-control" v-model="equipment.install_date"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2" >Warranty Expires On</label>

                                <div class="col-md-10">
                                    <input type="date" class="form-control" v-model="equipment.warranty_exp_date"/>
                                </div>
                            </div>
                        </div>
                    </fieldset>

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
                equipment:{
                    type: '', name: '', manufacturer: '', model_no: '', serial_no: '', install_date: '',
                    purchase_date: '', warranty_exp_date: '', purchased_from: '', customer_id: '',
                },
                equipment_id:this.$route.params.id,
                customers: [], locations: [], errors: [],
            }
        },
        created(){
            axios.get('customer/all').then(response => {
                this.customers = response.data;
            });
            axios.get('equipment/'+this.equipment_id).then(response=>{
                this.equipment=response.data;
            })
        },
        methods:{
            generateElements(id){
                axios.post('location/all', {id: id}).then(response => {
                    this.locations = response.data;
                })
            },
            editEquipment(){
                axios.post('equipment/edit/'+this.equipment_id,this.equipment).then(response=>{
                    this.errors = response.data.message
                    if(this.errors==undefined){
                        this.$router.push({name:'all-equipment'})
                    }
                })
            },
        }
    }
</script>
