<template>
    <div>
        <fieldset>
            <legend>Edit Contact</legend>
            <form class="form-horizontal mt-4" @submit.prevent="editContact">
                <div class="form-group">
                    <label class="control-label col-md-2">Name</label>

                    <div class="col-md-6">
                        <input class="form-control" v-model="contact.name"  placeholder="Name"/>
                    </div>

                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Job Title</label>

                    <div class="col-md-6">
                        <input class="form-control" v-model="contact.job_title" placeholder="Job Title"/>
                    </div>

                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Email</label>

                    <div class="col-md-6">
                        <input class="form-control"  v-model="contact.email" placeholder="Email"/>
                    </div>

                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Mobile Phone</label>

                    <div class="col-md-6">
                        <input class="form-control" v-model="contact.mobile_phone"  placeholder="Mobile Phone"/>
                    </div>

                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Home</label>

                    <div class="col-md-6">
                        <input class="form-control" v-model="contact.home_phone" placeholder="Home"/>
                    </div>

                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Fax</label>

                    <div class="col-md-6">
                        <input class="form-control" v-model="contact.fax" placeholder="Fax"/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-2">
                        <label class="checkbox"><input :disabled="contact.is_primary==1" type="checkbox" value="1" v-model.number="contact.is_primary"/>make primary</label>
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

        data(){
            return {
                contact:{
                    name:'',
                    job_title:'',
                    email:'',
                    mobile_phone:'',
                    home_phone:'',
                    fax:'',
                    is_primary:0,
                },
                contact_id:this.$route.params.id, errors: [],
            }
        },
        created(){
          this.populateFields();
        },

        methods:{
            editContact(){
                axios.post('customer/contact/edit/'+this.contact_id,this.contact).then(response=>{
                    this.errors = response.data.message
                    if(this.errors==undefined){
                        this.$router.push({name:'all-contact'})
                    }
                })
            },
            populateFields(){
                axios.get('contact/'+this.contact_id).then(response=>{
                    this.contact=response.data;
                })
            }
        }
    }
</script>

<style>

</style>