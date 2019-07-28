<template>

    <div class="container">
        <fieldset>
            <legend><h3>New Taxcode</h3></legend>
            <form class="form-horizontal" @submit.prevent="addTaxcode">

                <div class="form-group">
                    <label class="control-label col-md-2">Name</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="taxcode.name"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Agency</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="taxcode.agency"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Rate</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model.number="taxcode.rate"/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-md-offset-2">
                        <label class="checkbox"><input type="checkbox" value="1" v-model.number="taxcode.is_default"/>make default</label>
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
    import axios from 'axios'

    export default {
        data(){
            return{
                taxcode:{
                    name: '', agency: '', rate: '', is_default: 0,
                },
                errors: [],
            }
        },
        methods:{
            addTaxcode(){
                axios.post('tax-code/add', this.taxcode).then(response =>{
                    this.errors = response.data.message
                    console.log(response.data)
                    /*if(this.errors==undefined){
                        this.$router.push({name:'all-item'})
                    }*/
                });
            },
        },
    }

</script>