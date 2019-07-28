<template>
    <div class="container">
        <fieldset>
            <legend>Add Contract</legend>

            <form class="form-horizontal" @submit.prevent="editContract">
                <div class="form-group">
                    <label class="control-label col-md-2">Contract Name</label>

                    <div class="col-md-10">
                        <input class="form-control" v-model="contract.name"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Status</label>

                    <div class="col-md-10">
                        <select class="form-control" v-model.number="contract.status">
                            <option value="">Select Status</option>
                            <option value="1">Active</option>
                            <option value="2">Pending</option>
                            <option value="3">Cancelled</option>
                            <option value="4">Expired</option>
                            <option value="5">Closed</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Start Date</label>

                    <div class="col-md-5">
                        <input type="date" class="form-control" v-model="contract.start_date"/>
                    </div>
                </div>

                <div class="radio">
                    <label><input type="radio" value="" v-model="is_expire" @click="contract.end_date=''">No expiration</label>
                </div>

                <div class="radio">
                    <label><input type="radio" value="1" v-model="is_expire">Expire Date</label>
                </div>

                <div class="form-group" v-if="is_expire==1">
                    <label class="control-label col-md-2">End Date</label>

                    <div class="col-md-5">
                        <input type="date" class="form-control" v-model="contract.end_date"/>
                    </div>
                </div>

                <fieldset>
                    <legend>Customer Information</legend>
                    <div class="form-group">
                        <label class="control-label col-md-2" >Customer</label>

                        <div class="col-sm-10">
                            <select class="form-control" v-model.number="contract.customer_id" @change="generateElements(contract.customer_id)">
                                <option value="">Select a Customer</option>
                                <option v-for="customer in customers"
                                        :value="customer.id">{{customer.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2">Location</label>
                        <div class="col-sm-10">
                            <select v-model.number="contract.location_id"  class="form-control">
                                <option value="">Select a Location</option>
                                <option v-for="location in locations" :value="location.id" >{{location.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-sm-2">Contacts</label>
                        <div class="col-sm-10">
                            <select v-model.number="contract.contact_id"  class="form-control">
                                <option value="">Select a Contact</option>
                                <option v-for="contact in contacts" :value="contact.id" >{{contact.name}}</option>
                            </select>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Contract Limit</legend>

                    <div class="row">
                        <div class="col-md-1">

                        </div>

                        <div class="col-md-1">
                            <label class="control-label">Limit:</label>
                        </div>

                        <div class="col-md-1 radio">
                            <label class="control-label"><input type="radio" value="1" v-model.number="limit_type"
                                                                @click="contract.money_limit=''">Hours</label>
                        </div>

                        <div class="col-md-4">
                            <div class="col-md-6">
                                <input :disabled="limit_type!=1" class="form-control" v-model="contract.hour_limit">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">

                        </div>

                        <div class="col-md-1 radio">
                            <label class="control-label"><input type="radio" value="2" v-model.number="limit_type"
                                                                @click="contract.hour_limit=''">Money</label>
                        </div>

                        <div class="col-md-4">
                            <div class="col-md-6">
                                <input :disabled="limit_type!=2" class="form-control" v-model="contract.money_limit">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2">

                        </div>

                        <div class="col-md-1 radio">
                            <label class="control-label">
                                <input type="radio" value="3" v-model.number="limit_type"
                                       @click="contract.hour_limit='', contract.money_limit='', contract.unlimited=1">Unlimited</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-1">

                        </div>

                        <div class="col-md-1">
                            <label class="control-label">Overage</label>
                        </div>

                        <div class="col-md-4 checkbox">
                            <label class="control-label"><input type="checkbox" value="1" v-model.number="contract.overage_allow">Allow Overage</label>
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Contract Amount</legend>

                    <div class="form-group">
                        <label class="control-label col-md-2">Subtotal</label>

                        <div class="col-md-2">
                            <input class="form-control" v-model.number="contract.subtotal"
                                   @keyup="calculateTotal(contract.subtotal, contract.tax_code_id)"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Tax Code</label>
                        <div class="col-md-2">
                            <select v-model="contract.tax_code_id" class="form-control"
                                    @change="calculateTotal(contract.subtotal, contract.tax_code_id)">
                                <option value="">Tax Exempt</option>
                                <option v-for="taxcode in taxcodes" :value="taxcode.id" >{{taxcode.rate}}% {{taxcode.name}}</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Tax amount</label>

                        <div class="col-md-2">
                            <input disabled="disabled" class="form-control" v-model.number="tax"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Total</label>

                        <div class="col-md-2">
                            <input disabled="disabled" class="form-control" v-model="contract.total">
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <legend>Additional Information</legend>

                    <div class="form-group">
                        <label class="control-label col-md-2" >Contract Type</label>

                        <div class="col-md-5">
                            <select class="form-control" v-model.number="contract.type">
                                <option value="1">Type 1</option>
                                <option value="2">Type 2</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Contract No</label>

                        <div class="col-md-5">
                            <input class="form-control" v-model="contract.contract_no"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Purchase Order No</label>

                        <div class="col-md-5">
                            <input class="form-control" v-model="contract.purchase_order_no"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Contract Description</label>

                        <div class="col-md-10">
                            <textarea rows="3" class="form-control" v-model="contract.description"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Contract Terms</label>

                        <div class="col-md-10">
                            <textarea rows="3" class="form-control" v-model="contract.terms"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Contract Notes</label>

                        <div class="col-md-10">
                            <textarea rows="3" class="form-control" v-model="contract.notes"/>
                        </div>
                    </div>
                </fieldset>

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

    export default{
        created(){
            axios.get('contract/'+this.contract_id).then(response=>{

                this.contract=response.data;
                axios.post('location/all', {id: response.data.customer_id}).then(response => {
                    this.locations = response.data;
                })
                axios.post('contact-customer/all', {id: response.data.customer_id}).then(response => {
                    this.contacts = response.data;
                })

                if(this.contract.hour_limit!='')
                    this.limit_type = 1;

                if(this.contract.money_limit!='')
                    this.limit_type = 2;

                this.tax = this.contract.total - this.contract.subtotal;
            })
            axios.get('customer/all').then(response => {
                this.customers = response.data;
            })
            axios.get('tax-code/all').then(response => {
                this.taxcodes = response.data;
            })
        },
        data(){
            return{
                contract:{
                    name: '',
                    status: '',
                    start_date: '',
                    end_date: '',
                    hour_limit: '',
                    money_limit: '',
                    unlimited: '',
                    overage_allow: 0,
                    type: '',
                    contract_no: '',
                    purchase_order_no: '',
                    description: '',
                    terms: '',
                    notes: '',
                    subtotal: '',
                    total: '',
                    customer_id: '',
                    location_id: '',
                    contact_id: '',
                    tax_code_id: '',
                },
                is_expire: '', customers: [], locations: [], contacts: [], taxcodes: [],
                limit_type: '', tax: 0,
                contract_id:this.$route.params.id, errors: [],
            }
        },
        methods:{
            editContract(){
                axios.post('contract/edit/'+this.contract_id, this.contract).then(response =>{
                    this.errors = response.data.message
                    if(this.errors==undefined){
                        this.$router.push({name:'all-contract'});
                    }
                });
            },
            generateElements(id) {
                axios.post('location/all', {id: id}).then(response => {
                    this.locations = response.data;
                })
                axios.post('contact-customer/all', {id: id}).then(response => {
                    this.contacts = response.data;
                })
            },
            calculateTotal(subtotal, tax_id){
                axios.post('tax-code', {id: tax_id}).then(response => {
                    var taxRate = response.data.rate;
                    if(!taxRate)
                        this.tax = 0;
                    else
                        this.tax = subtotal*(taxRate/100);

                    this.contract.total = subtotal + this.tax;
                })
            }
        }
    }
</script>