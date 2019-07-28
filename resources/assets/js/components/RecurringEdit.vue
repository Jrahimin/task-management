<template>
    <div class="container">
        <fieldset>
            <legend>Edit Recurring Job</legend>

            <form class="form-horizontal" @submit.prevent="editRecurringWorkOrder">
                <div class="row">
                    <div id="workOrderLeft" class="form-group col-md-7">
                        <div class="panel panel-default">
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="control-label col-md-2">How Often</label>
                                    <div class="col-md-5">
                                        <select class="form-control" v-model.number="workorder.frequency" @change="frequencyChange">
                                            <option value="">Select Frequency</option>
                                            <option value="1">Daily</option>
                                            <option value="2">Weekly</option>
                                            <option value="3">Monthly</option>
                                            <option value="4">Once</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2">start</label>
                                    <div class="col-md-5">
                                        <input class="form-control" type="date" v-model="workorder.start_date"/>
                                    </div>
                                </div>

                                <div class="form-group" v-if="workorder.frequency!=4">

                                    <label class="control-label col-md-2">Every</label>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input class="form-control" type="text" v-model.number="workorder.interval"/>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="control-label col-md-2" v-if="workorder.frequency==1">Day(s)</label>
                                                <label class="control-label col-md-2" v-if="workorder.frequency==2">Week(s)</label>
                                                <label class="control-label col-md-2" v-if="workorder.frequency==3">Month(s)</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group" v-if="workorder.frequency==2">
                                    <label class="control-label col-md-2">On</label>
                                    <label class="checkbox-inline"><input type="checkbox" value="Sun" v-model="workorder.days_in_week">Sun</label>
                                    <label class="checkbox-inline"><input type="checkbox" value="Mon" v-model="workorder.days_in_week">Mon</label>
                                    <label class="checkbox-inline"><input type="checkbox" value="Tue" v-model="workorder.days_in_week">Tue</label>
                                    <label class="checkbox-inline"><input type="checkbox" value="Wed" v-model="workorder.days_in_week">Wed</label>
                                    <label class="checkbox-inline"><input type="checkbox" value="Thu" v-model="workorder.days_in_week">Thu</label>
                                    <label class="checkbox-inline"><input type="checkbox" value="Fri" v-model="workorder.days_in_week">Fri</label>
                                    <label class="checkbox-inline"><input type="checkbox" value="Sat" v-model="workorder.days_in_week">Sat</label>
                                </div>

                                <div class="form-group" v-if="workorder.frequency==3">
                                    <div class="row">

                                        <label class="control-label col-md-2">On the</label>
                                        <div class="form-group col-md-1">
                                            <input class="radio-inline" type="radio" value="1" v-model="month_choice"
                                                   @click="workorder.day_in_month=''">
                                        </div>

                                        <div class="form-group col-md-7">
                                            <div class="col-md-6">
                                                <select :disabled="month_choice!=1" class="form-control" v-model.number="workorder.part_of_month">
                                                    <option value="1">First</option>
                                                    <option value="2">Second</option>
                                                    <option value="3">Third</option>
                                                    <option value="4">Fourth</option>
                                                    <option value="5">Last</option>
                                                </select>
                                            </div>

                                            <div class="col-md-6">
                                                <select :disabled="month_choice!=1" class="form-control" v-model.number="workorder.week_in_month">
                                                    <option value="Sun">Sunday</option>
                                                    <option value="Mon">Monday</option>
                                                    <option value="Tue">Tuesday</option>
                                                    <option value="Wed">Wednesday</option>
                                                    <option value="Thu">Thursday</option>
                                                    <option value="Fri">Friday</option>
                                                    <option value="Sat">Saturday</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <label class="control-label col-md-2"></label>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <input type="radio" value="2" v-model="month_choice"
                                                       @click="workorder.part_of_month='', workorder.week_in_month=''">
                                            </div>
                                        </div>

                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <select :disabled="month_choice!=2" class="form-control" v-model.number="workorder.day_in_month">
                                                    <option value="1">1st</option>
                                                    <option value="2">2nd</option>
                                                    <option value="3">3rd</option>
                                                    <option value="4">4th</option>
                                                    <option value="5">5th</option>
                                                    <option value="6">6th</option>
                                                    <option value="7">7th</option>
                                                    <option value="8">8th</option>
                                                    <option value="9">9th</option>
                                                    <option value="10">10th</option>
                                                    <option value="11">11th</option>
                                                    <option value="12">12th</option>
                                                    <option value="13">13th</option>
                                                    <option value="14">14th</option>
                                                    <option value="15">15th</option>
                                                    <option value="16">16th</option>
                                                    <option value="17">17th</option>
                                                    <option value="18">18th</option>
                                                    <option value="19">19th</option>
                                                    <option value="20">20th</option>
                                                    <option value="21">21st</option>
                                                    <option value="22">22nd</option>
                                                    <option value="23">23rd</option>
                                                    <option value="24">24th</option>
                                                    <option value="25">25th</option>
                                                    <option value="26">26th</option>
                                                    <option value="27">27th</option>
                                                    <option value="28">28th</option>
                                                    <option value="29">29th</option>
                                                    <option value="30">30th</option>
                                                    <option value="31">31st</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group"><label class="control-label">of the month</label></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2">Occurs At</label>
                                    <div class="col-md-5">
                                        <input class="form-control" type="time" step="900" v-model="workorder.time"/>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-md-2">End</label>

                                    <div class="col-sm-5">
                                        <label class="radio-inline"><input type="radio" v-model.number="end_on" value="1"
                                                                           @click="endUntillStop">Untill Stopped</label>
                                        <label class="radio-inline"><input type="radio" v-model.number="end_on" value="2"
                                                                           @click="workorder.end_occurance=''">On</label>
                                        <label class="radio-inline"><input type="radio" v-model.number="end_on" value="3"
                                                                           @click="workorder.end_date=''">After</label>
                                    </div>
                                </div>


                                <div class="form-group">
                                    <div class="col-md-5 col-lg-offset-2">
                                        <input class="form-control" v-if="end_on==2" type="date" v-model="workorder.end_date"/>

                                        <div class="row col-md-12" v-if="end_on==3">
                                            <div class="col-md-8">
                                                <input class="form-control" type="text" v-model.number="workorder.end_occurance"/>
                                            </div>
                                            <div class="col-md-2">
                                                <label class="control-label">Occurances</label>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-body">
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
                                    <label class="control-label col-sm-2">Locations</label>
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
                                    <label class="control-label col-md-2">Purchase Order No</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" v-model="workorder.purchase_order_no"/>
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
    import axios from 'axios';

    export default {
        components: {
        },
        data() {
            return {
                workorder: {
                    description: '',
                    type: '',
                    priority: '',
                    detailed_description: '',
                    is_billable: '',
                    bill_to: '',
                    purchase_order_no: '',
                    status: '',
                    customer_id: '',
                    contact_id: '',
                    contract_id: '',
                    location_id: '',
                    tax_code_id: '',
                    service_manager_id: '',
                    account_manager_id: '',
                    related_person_id: '',
                    equipment_ids: [],
                    user_ids: [],

                    customer_location_id: '',
                    location_name: '',
                    address: '',
                    city: '',
                    state: '',
                    zip: '',
                    country: '',
                    zone: '',
                    is_assigned: 0,
                    is_scheduled: 0,

                    interval: '',
                    frequency: '',
                    start_date: '',
                    end_date: null,
                    time: '',
                    end_occurance: '',
                    week_in_month: null,
                    days_in_week: [],
                    day_in_month: null,
                    part_of_month: null,
                },
                customers: [],
                locations: [],
                contacts: [],
                equipments: [],
                contracts: [],
                other_locations: [],
                taxcodes: [],
                users: [],
                recurring_id:this.$route.params.id,
                other_customer_id: '',
                other_location: false,
                different_customer: false,
                end_on: '',
                month_choice: '',
                errors: [],
            }
        },
        created() {
            axios.get('recurring/'+this.recurring_id).then(response=>{
                    this.workorder.description=response.data.description;
                    this.workorder.type=response.data.type;
                    this.workorder.priority=response.data.priority;
                    this.workorder.detailed_description=response.data.detailed_description;
                    this.workorder.is_billable=response.data.is_billable;
                    this.workorder.bill_to=response.data.bill_to;
                    this.workorder.purchase_order_no=response.data.purchase_order_no;
                    this.workorder.status=response.data.status;
                    this.workorder.customer_id=response.data.customer_id;
                    this.workorder.equipment_ids=response.data.equipments;
                axios.post('location/all', {id: this.workorder.customer_id}).then(response => {
                    this.locations = response.data;
                })
                axios.post('contact-customer/all', {id: this.workorder.customer_id}).then(response => {
                    this.contacts = response.data;
                })
                axios.post('equipment-customer',{id:this.workorder.customer_id}).then(response => {
                    this.equipments = response.data;
                    //console.log(response.data)
                })
                axios.post('contract-customer', {id: this.workorder.customer_id}).then(response => {
                    this.contracts = response.data;
                    //console.log(response.data)
                })

                    this.workorder.contact_id=response.data.contact_id;
                    this.workorder.contract_id=response.data.contract_id;
                    this.workorder.location_id=response.data.location_id;
                    this.workorder.tax_code_id=response.data.tax_code_id;
                    this.workorder.service_manager_id=response.data.service_manager_id;
                    this.workorder.account_manager_id=response.data.account_manager_id;
                    this.workorder.related_person_id=response.data.related_person_id;
                    this.workorder.user_ids=response.data.user_ids;

                    this.workorder.interval=response.data.interval;
                    this.workorder.frequency=response.data.frequency;
                    this.workorder.start_date=response.data.start_date;
                    this.workorder.end_date=response.data.end_date;
                    this.workorder.time=response.data.time;
                    this.workorder.end_occurance=response.data.end_occurance;
                    this.workorder.week_in_month=response.data.week_in_month;
                    this.workorder.days_in_week=response.data.days_in_week;
                    this.workorder.day_in_month=response.data.day_in_month;
                    this.workorder.part_of_month=response.data.part_of_month;

            });
            axios.get('customer/all').then(response => {
                this.customers = response.data;
            });
            axios.get('user/all').then(response => {
                this.users = response.data;
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
            endUntillStop(){
                this.workorder.end_date = '';
                this.workorder.end_occurance = '';
            },
            frequencyChange(){
                this.workorder.days_in_week = [];
                this.workorder.part_of_month = '';
                this.workorder.day_in_month = '';
                this.workorder.week_in_month = '';
                this.workorder.interval = '';
            },
            editRecurringWorkOrder() {
                axios.post('recurring-job/edit/'+this.recurring_id, this.workorder).then(response =>{
                    this.errors = response.data.message
                    if(this.errors==undefined){
                        this.$router.push({name:'recurring-all'})
                    }
                });
            }
        },
    }
</script>

<style>

</style>