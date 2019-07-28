<template>
    <div class="container">
        <fieldset>
            <legend>New Work Order</legend>

            <form class="form-horizontal" @submit.prevent="addWorkOrder">
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
                            <label class="control-label col-md-2">Status</label>

                            <div class="col-md-5">
                                <select class="form-control" v-model.number="workorder.status">
                                    <option value="">Select Status</option>
                                    <option value="1">New</option>
                                    <option value="2">Assigned</option>
                                    <option value="3">Postponed</option>
                                    <option value="4">Completed</option>
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
                            <label class="control-label col-sm-2">Location</label>
                            <div class="col-sm-10">
                                <select v-model.number="workorder.location_id"  class="form-control">
                                    <option value="">Select a Location</option>
                                    <option v-for="location in locations" :value="location.id" >{{location.name}}</option>
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

                        <div class="form-group">
                            <label class="control-label col-sm-2">Billable Status</label>
                            <div class="col-sm-5">
                                <label class="radio-inline"><input type="radio" v-model.number="workorder.is_billable" value="1"
                                                                   @click="workorder.bill_to=4,different_customer=false, other_location=false">Billable</label>
                                <label class="radio-inline"><input type="radio" v-model.number="workorder.is_billable" value="0"
                                                                   @click="nonBillable">Non-Billable</label>
                            </div>
                        </div>

                        <div class="form-group" v-if="workorder.is_billable==1">
                            <label class="control-label col-md-2">Bill To</label>
                            <div class="col-md-10">
                                <label class="radio-inline"><input type="radio" value="4" v-model="workorder.bill_to"
                                                                   @click="different_customer=false, other_location=false"/>Default Billing</label>

                                <label class="radio-inline"><input type="radio"   value="2" v-model="workorder.bill_to"
                                                                   @click="other_location=true, different_customer=false"/>Enter Manually</label>

                                <label class="radio-inline"><input type="radio" value="3"  v-model="workorder.bill_to" @click="differentCustomer"/>Address from Customer List</label>
                            </div>
                        </div>

                        <div class="form-group" v-if="other_location">

                            <div class="form-group">
                                <label class="control-label col-md-2">Location Name</label>

                                <div class="col-md-10">
                                    <input class="form-control"  v-model="workorder.location_name" placeholder="Location Name"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2">Address</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="workorder.address" placeholder="Address"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2">City</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="workorder.city"  placeholder="City"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2">State</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="workorder.state" placeholder="State"/>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-md-2">Zip</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="workorder.zip" placeholder="Zip"/>
                                </div>
                            </div>


                            <div class="form-group">
                                <label class="control-label col-md-2">Country</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="workorder.country" placeholder="Country"/>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2">Zone</label>

                                <div class="col-md-10">
                                    <input class="form-control" v-model="workorder.zone" placeholder="Zone"/>
                                </div>
                            </div>

                        </div>

                        <div class="form-group" v-if="different_customer">

                            <div class="form-group">
                                <label class="control-label col-md-2">Customer</label>
                                <div class="col-md-10">
                                    <select v-model.number="other_customer_id" @change="differentCustomerLocation(other_customer_id)" class="form-control">
                                        <option v-for="customer in customers" :value="customer.id" >{{customer.name}}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="control-label col-md-2">Locations</label>
                                <div class="col-md-10">
                                    <select v-model.number="workorder.customer_location_id"  class="form-control">
                                        <option v-for="another_location in other_locations" :value="another_location.id" >{{another_location.address}}, {{another_location.name}}</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Tax code</label>
                            <div class="col-md-5">
                                <select v-model="workorder.tax_code_id" class="form-control">
                                    <option value="">Tax Exempt</option>
                                    <option v-for="taxcode in taxcodes" :value="taxcode.id" >{{taxcode.rate}}% {{taxcode.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Contracts</label>
                            <div class="col-md-10">
                                <select v-model.number="workorder.contract_id" class="form-control">
                                    <option v-for="contract in contracts" :value="contract.id" >{{contract.name}}</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Purchase Order No</label>
                            <div class="col-md-10">
                                <input class="form-control" type="text" v-model="workorder.purchase_order_no"/>
                            </div>
                        </div>
                    </div>

                    <div id="workOrderRight" class="form-group col-md-5">

                        <div class="form-group">
                            <div class="col-md-6">
                                <label class="checkbox"><input type="checkbox" value="1" v-model="workorder.is_assigned"/>Assign to</label>
                            </div>
                        </div>

                        <div class="form-group" v-if="workorder.is_assigned==1">

                            <div class="form-group col-md-8">
                                <select multiple v-model.number="workorder.user_ids" class="form-control">
                                    <option v-for="user in users" :value="user.id" >{{user.username}}</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <div class="col-md-6">
                                    <label class="radio-inline"><input type="radio" value="0" v-model.number="workorder.is_scheduled">Unscheduled</label>
                                    <label class="radio-inline"><input type="radio" value="1" v-model.number="workorder.is_scheduled">Scheduled</label>
                                </div>
                            </div>

                            <div class="form-group" v-if="workorder.is_scheduled==1">
                                <div class="form-group row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">start</label>
                                            <div class="col-md-6">
                                                <input type="date" v-model="workorder.start_date"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3" v-if="workorder.is_all_day!=1">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <input type="time" v-model="workorder.start_time"/>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <div class="col-md-7">
                                        <div class="form-group">
                                            <label class="control-label col-md-4">end</label>
                                            <div class="col-md-6">
                                                <input type="date" v-model="workorder.end_date"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3" v-if="workorder.is_all_day!=1">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <input type="time" v-model="workorder.end_time"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <div class="col-md-6">
                                                <label class="checkbox pull-right"><input type="checkbox" value="1" v-model.number="workorder.is_all_day"/>All day</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                            </div>
                        </div>

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

                <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
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
    import axios from 'axios';

    export default {
        components: {},
        data() {
            return {
                workorder: {
                    description: '', type: '', priority: '', detailed_description: '', is_billable: '', bill_to: '',
                    purchase_order_no: '', status: '', customer_id: '', contact_id: '', contract_id: '',
                    location_id: '', tax_code_id: '', service_manager_id: '', account_manager_id: '',
                    related_person_id: '', recurring_work_order_id: '', equipment_ids: [], user_ids: [],
                    start_date: '', end_date: '', start_time: '', end_time: '', is_all_day: '',
                    customer_location_id: '', location_name: '', address: '', city: '', state: '',
                    zip: '', country: '', zone: '', is_assigned: 0, is_scheduled: 0,
                },
                customers: [], locations: [], contacts: [], equipments: [], contracts: [], other_locations: [],
                taxcodes: [], users: [], errors: [],
                other_customer_id: '', other_location: false, different_customer: false,
            }
        },
        created() {
            axios.get('customer/all').then(response => {
                this.customers = response.data;
            });
            axios.get('user/all').then(response => {
                this.users = response.data;
            })
            axios.get('tax-code/all').then(response => {
                this.taxcodes = response.data;
            })
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
                axios.post('contract-customer', {id: id}).then(response => {
                    this.contracts = response.data;
                    //console.log(response.data)
                })
            },
            differentCustomer() {
                this.different_customer = true;
                this.other_location = false;
            },
            differentCustomerLocation(id) {
                axios.post('location/all', {id: id}).then(response => {
                    this.other_locations = response.data;
                })
            },
            nonBillable(){
                    this.workorder.customer_location_id = ''; this.workorder.location_name = '';
                    this.workorder.address = ''; this.workorder.city = ''; this.workorder.state = '';
                    this.workorder.zip = ''; this.workorder.country = ''; this.workorder.zone = '';
                    this.other_customer_id = ''; this.workorder.bill_to = 0;
                    this.other_location = false; this.different_customer = false;
            },
            addWorkOrder() {
                if(this.workorder.status == 2)
                {
                    if(this.workorder.is_assigned == 0 )
                        alert("Please assign an user for assigned work order");
                }

                axios.post('work-order/add', this.workorder).then(response =>{
                    this.errors = response.data.message
                    if(this.errors==undefined){
                        this.$router.push({name:'recurring-all'})
                    }
                });
            }
        }
    }
</script>

<style>

</style>