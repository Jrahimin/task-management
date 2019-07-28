<template>

    <div>

        <form class="form-horizontal mt-4" @submit.prevent="editCustomer">
            <fieldset>
                <legend>Edit Customer</legend>

            <div class="form-group">

                <label class="control-label col-md-2" >Customer Name</label>


                <div class="col-md-6">
                    <input class="form-control" v-model="customer.name" placeholder="Customer Name"/>
                </div>
            </div>

            <div class="form-group">

                <label class="control-label col-md-2" >Status</label>
                <div class="col-md-6 checkbox">
                    <label class="control-label"><input type="checkbox" value="1" v-model.number="customer.status" />
                        <span v-if="customer.status">Active</span><span v-if="customer.status==0">Not Active</span></label>
                </div>
            </div>
            <h3>Contact</h3>
            <div class="form-group">
                <label class="control-label col-md-2">Name</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.contact_name"  placeholder="Name"/>
                </div>

            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Job Title</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.job_title" placeholder="Job Title"/>
                </div>

            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Email</label>

                <div class="col-md-6">
                    <input class="form-control"  v-model="customer.email" placeholder="Email"/>
                </div>

            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Mobile Phone</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.mobile_phone"  placeholder="Mobile Phone"/>
                </div>

            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Home</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.home_phone" placeholder="Home"/>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Fax</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.fax" placeholder="Fax"/>
                </div>

            </div>
            <h3>Location</h3>
            <div class="form-group">
                <label class="control-label col-md-2">Location Name</label>

                <div class="col-md-6">
                    <input class="form-control"  v-model="customer.location_name" placeholder="Location Name"/>
                </div>

            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Address</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.address" placeholder="Address"/>
                </div>

            </div>

            <div class="form-group">
                <label class="control-label col-md-2">City</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.city"  placeholder="City"/>
                </div>

            </div>

            <div class="form-group">
                <label class="control-label col-md-2">State</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.state" placeholder="State"/>
                </div>

            </div>


            <div class="form-group">
                <label class="control-label col-md-2">Zip</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.zip" placeholder="Zip"/>
                </div>

            </div>


            <div class="form-group">
                <label class="control-label col-md-2">Country</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.country" placeholder="Country"/>
                </div>

            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Zone</label>

                <div class="col-md-6">
                    <input class="form-control" v-model="customer.zone" placeholder="Zone"/>
                </div>

            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Account Manager</label>
                <div class="col-md-6">
                    <select v-model.number="customer.account_manager_id" class="form-control">
                        <option value="">Select an Account Manager</option>
                        <option v-for="manager1 in managers" :value="manager1.id">{{manager1.username}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="control-label col-md-2">Service Manager</label>
                <div class="col-md-6">
                    <select v-model.number="customer.service_manager_id" class="form-control">
                        <option value="">Select a Service Manager</option>
                        <option v-for="manager2 in managers" :value="manager2.id">{{manager2.username}}</option>
                    </select>
                </div>
            </div>
            <h3>Billing Information</h3>
            <div class="form-group">
                <label class="control-label col-md-2">Bill To</label>
                <div class="col-md-10">
                    <label>
                        <input type="radio" value="1" v-model="customer.bill_to"
                               @click="other_location=false, different_customer=false"/>Bill to primary location
                    </label>

                    <label>
                        <input type="radio"   value="2" v-model="customer.bill_to"
                               @click="other_location=true, different_customer=false"/>Bill to other addresses
                    </label>

                    <label>
                        <input type="radio" value="3"  v-model="customer.bill_to" @click="differentCustomer"/>Bill to a different customer
                    </label>
                    <div v-show="other_location">
                        <div class="form-group">
                            <label class="control-label col-md-2">Location Name</label>

                            <div class="col-md-6">
                                <input class="form-control"  v-model="customer.other_location_name" placeholder="Location Name"/>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Address</label>

                            <div class="col-md-6">
                                <input class="form-control" v-model="customer.other_address" placeholder="Address"/>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">City</label>

                            <div class="col-md-6">
                                <input class="form-control" v-model="customer.other_city"  placeholder="City"/>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">State</label>

                            <div class="col-md-6">
                                <input class="form-control" v-model="customer.other_state" placeholder="State"/>
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Zip</label>

                            <div class="col-md-6">
                                <input class="form-control" v-model="customer.other_zip" placeholder="Zip"/>
                            </div>

                        </div>


                        <div class="form-group">
                            <label class="control-label col-md-2">Country</label>

                            <div class="col-md-6">
                                <input class="form-control" v-model="customer.other_country" placeholder="Country"/>
                            </div>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Zone</label>

                            <div class="col-md-6">
                                <input class="form-control" v-model="customer.other_zone" placeholder="Zone"/>
                            </div>

                        </div>

                    </div>

                    <div v-show="different_customer">
                        <label class="control-label col-md-2">Customer</label>
                        <div class="col-md-6">
                            <select v-model.number="other_customer_id" @change="differentCustomerLocation(other_customer_id)" class="form-control">
                                <option value="">Select a Customer</option>
                                <option v-for="customer in customers" :value="customer.id" >{{customer.name}}</option>
                            </select>
                        </div>
                        <label class="control-label col-md-2">Locations</label>
                        <div class="col-md-6">
                            <select v-model.number="customer.customer_location_id"  class="form-control">
                                <option value="">Select a location</option>
                                <option v-for="location in locations" :value="location.id" >{{location.name}}</option>
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
            </fieldset>
        </form>

    </div>
</template>

<script>
    import Contact from './Contact.vue'
    import Location from './Location.vue'
    import axios from 'axios'
    export default {

        data() {
            return {
                customer: {
                    name: '',
                    status:2,
                    account_manager_id: '',
                    service_manager_id: '',
                    bill_to: '',
                    location_name: '',
                    address: '',
                    city: '',
                    state: '',
                    zip: '',
                    country: '',
                    zone: '',
                    contact_name: '',
                    job_title: '',
                    email: '',
                    mobile_phone: '',
                    home_phone: '',
                    fax: '',
                    other_location_name: '',
                    other_address: '',
                    other_city: '',
                    other_state: '',
                    other_zip: '',
                    other_country: '',
                    other_zone: '',
                    customer_location_id: ''
                },
                id : this.$route.params.id,
                managers: [],
                other_location: false,
                different_customer: false,
                customers: [],
                other_customer_id: '',
                locations: [],
                errors: [],

            }
        },
        created() {
            this.populateFields();
            this.populateManagers();
        },
        methods: {
            editCustomer() {
                axios.post('customer-edit/'+this.id, this.customer).then(response => {
                    this.errors = response.data.message
                    if(this.errors==undefined){
                        this.$router.push({name:'customer-all'});
                    }
                })

            },
            populateFields() {
                axios.get('customer-show/'+this.id).then(response => {
                   this.customer.name = response.data.name;
                   this.customer.status=response.data.status;
                   this.customer.account_manager_id=response.data.account_manager_id;
                   this.customer.service_manager_id=response.data.service_manager_id;
                });

                axios.get('customer-contact/'+this.id).then(response=>{
                 this.customer.contact_name=response.data.name;
                 this.customer.job_title=response.data.job_title;
                 this.customer.email=response.data.email;
                 this.customer.home_phone=response.data.home_phone;
                 this.customer.mobile_phone=response.data.mobile_phone;
                 this.customer.fax=response.data.fax;
                });
                axios.get('customer-location/'+this.id).then(response=>{
                    console.log(response.data);
                    this.customer.location_name=response.data.name;
                    this.customer.address=response.data.address;
                    this.customer.city=response.data.city;
                    this.customer.state=response.data.state;
                    this.customer.zip=response.data.zip;
                    this.customer.zone=response.data.zone;
                    this.customer.country=response.data.country;

                })

            },
            populateManagers() {
                axios.get('user/all').then(response => {
                    this.managers = response.data;
                })
            },
            differentCustomer() {
                this.different_customer = true;
                this.other_location = false;
                axios.get('customer/all').then(response => {
                    this.customers = response.data;
                })
            },
            differentCustomerLocation(id) {
                axios.post('location/all', {id: id}).then(response => {
                    this.locations = response.data;
                    // console.log(response.data)
                })
            }
        }

    }

</script>

<style>

</style>