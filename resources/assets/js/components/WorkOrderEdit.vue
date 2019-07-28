<template>
    <div class="container">
        <fieldset>
            <legend>Edit Work Order</legend>

            <form class="form-horizontal" @submit.prevent="editWorkOrder">
                <div class="row">
                    <div id="workOrderLeft" class="form-group col-md-7">
                        <div class="form-group">
                            <label class="control-label col-md-2" >Description</label>

                            <div class="col-md-10">
                                <textarea rows="2" class="form-control" v-model="workorder.description"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Type</label>
                            <div class="col-md-5">
                                <select class="form-control" v-model.number="workorder.type">
                                    <option value="">Select Type</option>
                                    <option value="1">New Install</option>
                                    <option value="2">Problem</option>
                                    <option value="3">Maintenance</option>
                                    <option value="4">Follow Up</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Priority</label>

                            <div class="col-md-5">
                                <select class="form-control" v-model.number="workorder.priority">
                                    <option value="">Select Priority</option>
                                    <option value="1">Critical</option>
                                    <option value="2">High</option>
                                    <option value="3">Normal</option>
                                    <option value="4">Low</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2" >Customer</label>

                            <div class="col-sm-10">
                                <select class="form-control" v-model.number="workorder.customer_id" @change="generateElements(workorder.customer_id)">
                                    <option value="">Select a Customer</option>
                                    <option v-for="customer in customers"
                                            :value="customer.id">{{customer.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Locations</label>
                            <div class="col-sm-10">
                                <select v-model.number="workorder.location_id"  class="form-control">
                                    <option value="">Select a Location</option>
                                    <option v-for="location in locations" :value="location.id">{{location.name}} {{location.address}} {{location.city}} </option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Contacts</label>
                            <div class="col-sm-10">
                                <select v-model.number="workorder.contact_id"  class="form-control">
                                    <option value="">Select a Contact</option>
                                    <option v-for="contact in contacts" :value="contact.id" >{{contact.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2" > Detailed Description</label>

                            <div class="col-md-10">
                                <textarea rows="4" class="form-control" v-model="workorder.detailed_description"/>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-2">Equipment to Service</label>
                            <div class="col-md-10">
                                <select multiple class="form-control" v-model.number="workorder.equipment_ids">
                                    <option v-for="equipment in equipments" :value="equipment.id" >{{equipment.name}}</option>
                                </select>
                            </div>
                        </div>
                    </div>



                    <div id="workOrderRight" class="form-group col-md-5">

                        <div class="form-group">
                            <h3>Related To</h3><hr>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4">Service manager</label>
                            <div class="form-group col-md-6">
                                <select v-model.number="workorder.service_manager_id" class="form-control">
                                    <option v-for="user in users" :value="user.id" >{{user.username}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4">Account Manager</label>
                            <div class="form-group col-md-6">
                                <select v-model.number="workorder.account_manager_id" class="form-control">
                                    <option v-for="user in users" :value="user.id" >{{user.username}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-4">Other Related People</label>
                            <div class="form-group col-md-6">
                                <select v-model.number="workorder.related_person_id" class="form-control">
                                    <option v-for="user in users" :value="user.id" >{{user.username}}</option>
                                </select>
                            </div>
                        </div>

                    </div>

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
    import axios from 'axios';

    export default {
        components: {},
        data() {
            return {
                workorder: {
                    description: '',
                    type: '',
                    priority: '',
                    detailed_description: '',
                    customer_id: '',
                    contact_id: '',
                    location_id: '',
                    service_manager_id: '',
                    account_manager_id: '',
                    related_person_id: '',
                    equipment_ids: [],
                    user_ids: [],
                },
                work_order_id:this.$route.params.id,
                customers: [],
                locations: [],
                contacts: [],
                equipments: [],
                users: [],

            }
        },
        created() {
            axios.get('work-order/'+this.work_order_id).then(response=>{
                this.workorder.description=response.data.description;
                    this.workorder.type=response.data.type;
                    this.workorder.priority=response.data.priority;
                    this.workorder.detailed_description=response.data.detailed_description;
                    this.workorder.customer_id=response.data.customer_id;
                        axios.post('location/all', {id: this.workorder.customer_id}).then(response => {
                        this.locations = response.data;
                   });
                      axios.post('contact-customer/all', {id: this.workorder.customer_id}).then(response => {
                       this.contacts = response.data;
                   })
                    this.workorder.contact_id=response.data.contact_id;
                    this.workorder.location_id=response.data.location_id;
                    this.workorder.service_manager_id=response.data.service_manager_id;
                    this.workorder.account_manager_id=response.data.account_manager_id;
                    this.workorder.related_person_id=response.data.related_person_id;
                    this.workorder.user_ids=response.data.user_ids;
            })
            axios.get('customer/all').then(response => {
                this.customers = response.data;
            });
            axios.get('user/all').then(response => {
                this.users = response.data;
            });
        },
        methods: {
            generateElements(id) {
                axios.post('location/all', {id: id}).then(response => {
                    this.locations = response.data;
                })
                axios.post('contact-customer/all', {id: id}).then(response => {
                    this.contacts = response.data;
                })
                axios.post('equipment-customer', {id: id}).then(response => {
                    this.equipments = response.data;
                    //console.log(response.data)
                })
            },
            editWorkOrder() {
                axios.post('work-order/edit/'+this.work_order_id, this.workorder).then(response =>{
                    this.$router.push({name:'workorder-all'});
                });
            }
        }
    }
</script>

<style>
    .form-control{
        height: 34px;
    }
</style>