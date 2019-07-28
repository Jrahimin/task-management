<template>
    <div>
        <form class="form-horizontal mt-4" @submit.prevent="addContact">
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
                    <label class="checkbox"><input type="checkbox" v-model="contact.is_primary"/>Add as Primary</label>
                </div>
            </div>

            <div class="form-group alert alert-danger" v-if="errors!=''">
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-2">
                    <button  class="btn btn-primary pull-right" type="submit">Save</button>
                </div>
            </div>

        </form>
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
                is_primary:false
            },
            customer_id:this.$route.params.id, errors: [],
        }
    },

    methods:{
        addContact(){
            axios.post('customer/contact/add/'+this.customer_id,this.contact).then(response=>{
                this.errors = response.data.message
                if(this.errors==undefined){
                    this.$router.push({name:'all-contact'})
                }
            })
        }
    }
}
</script>

<style>

</style>