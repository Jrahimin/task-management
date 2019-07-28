<template>
    <div>
        <form class="form-horizontal mt-4" @submit.prevent="addLocation">
        <div class="form-group">
            <label class="control-label col-md-2">Location Name</label>

            <div class="col-md-6">
                <input class="form-control"  v-model="location.name" placeholder="Location Name"/>
            </div>

        </div>

        <div class="form-group">
            <label class="control-label col-md-2">Address</label>

            <div class="col-md-6">
                <input class="form-control" v-model="location.address" placeholder="Address"/>
            </div>

        </div>

        <div class="form-group">
            <label class="control-label col-md-2">City</label>

            <div class="col-md-6">
                <input class="form-control" v-model="location.city"  placeholder="City"/>
            </div>

        </div>

        <div class="form-group">
            <label class="control-label col-md-2">State</label>

            <div class="col-md-6">
                <input class="form-control" v-model="location.state" placeholder="State"/>
            </div>

        </div>


        <div class="form-group">
            <label class="control-label col-md-2">Zip</label>

            <div class="col-md-6">
                <input class="form-control" v-model="location.zip" placeholder="Zip"/>
            </div>

        </div>


        <div class="form-group">
            <label class="control-label col-md-2">Country</label>

            <div class="col-md-6">
                <input class="form-control" v-model="location.country" placeholder="Country"/>
            </div>

        </div>

        <div class="form-group">
            <label class="control-label col-md-2">Zone</label>

            <div class="col-md-6">
                <input class="form-control" v-model="location.zone" placeholder="Zone"/>
            </div>

        </div>
            <div class="form-group">


                <div class="col-md-6">
                    <input type="checkbox" class="form-control"  v-model="location.is_primary" /> Primary
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
    </div>
</template>

<script>
    import axios from 'axios';
export default {

    data(){
        return{
            location:{
                name:'',
                address:'',
                city:'',
                state:'',
                zip:'',
                country:'',
                zone:'',
                is_primary:false
            },
            customer_id:this.$route.params.id, errors: [],
        }
    },
    methods:{
        addLocation(){
            axios.post('customer/location/add/'+this.customer_id,this.location).then(response=>{
                this.errors = response.data.message
                if(this.errors==undefined){
                    this.$router.push({name:'all-location'})
                }
            })
        }
    }
}
</script>

<style>

</style>