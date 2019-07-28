<template>

    <div class="container">
        <fieldset>
            <legend><h3>Edit Item</h3></legend>
            <form class="form-horizontal" @submit.prevent="editItem">

                <div class="form-group">
                    <label class="control-label col-md-2">Item Type</label>
                    <div class="col-md-6">
                        <select v-model.number="item.type"  class="form-control"
                                @change="typeChange">
                            <option value="1">Labor Rate</option>
                            <option value="2">Materials Item</option>
                            <option value="3">Expense Item</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Name</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="item.name"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Description</label>
                    <div class="col-md-6">
                        <textarea rows="3" class="form-control" v-model="item.description"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Price</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="item.price"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Cost</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="item.cost"/>
                    </div>
                </div>

                <div v-if="item.type==2 || item.type==3">
                    <div class="form-group">
                        <hr style="border-color: snow;"/>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Manufacturer</label>
                        <div class="col-md-6">
                            <input class="form-control" v-model="item.manufacturer"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Mfr. Item No.</label>
                        <div class="col-md-6">
                            <input class="form-control" v-model="item.mfr_item_no"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">Mfr. Item Desc.</label>
                        <div class="col-md-6">
                            <input class="form-control" v-model="item.mfr_item_description"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-2">List Price</label>
                        <div class="col-md-6">
                            <input class="form-control" v-model="item.list_price"/>
                        </div>
                    </div>

                    <div class="form-group">
                        <hr style="border-color: snow;"/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="checkbox col-md-6">
                        <label class="control-label col-md-4 checkbox">Status</label>
                        <div class="col-md-4">
                            <label class="control-label"><input type="checkbox" value="1" v-model="item.status"/>
                                Discontinued</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="checkbox col-md-6">
                        <label class="control-label col-md-4 checkbox">Taxable status</label>
                        <div class="col-md-4">
                            <label class="control-label"><input type="checkbox" value="1" v-model="item.taxable"/>
                                Taxable by default</label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Note</label>
                    <div class="col-md-6">
                        <textarea rows="3" class="form-control" v-model="item.note"/>
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
    import axios from 'axios'

    export default {
        data(){
            return{
                item:{
                    type: 1,name: '',description: '',price: 0.00,cost: 0.00,status: '',taxable: '',note: '',
                    manufacturer: '',mfr_item_no: '',mfr_item_description: '',list_price: '',
                },
                item_id:this.$route.params.id, errors: [],
            }
        },
        methods:{
            editItem(){
                axios.post('item/edit/'+this.item_id, this.item).then(response =>{
                    this.errors = response.data.message
                    if(this.errors==undefined){
                        this.$router.push({name:'all-item'})
                    }
                });
            },
            typeChange(){
                if(this.item.type==1){
                    this.item.manufacturer = '';
                    this.item.mfr_item_no = '';
                    this.item.mfr_item_description = '';
                    this.item.list_price = '';
                }
            },
        },
        created(){
            axios.get('item/'+this.item_id, this.item).then(response =>{
                this.item = response.data;
            });
        },
    }

</script>

<style>

</style>