<template>

    <div class="container">
        <fieldset>
            <legend><h3>Add Item </h3></legend>
            <form class="form-horizontal" @submit.prevent="assignItem">

                <div class="form-group">
                    <label class="control-label col-md-2">Item Type</label>
                    <div class="col-md-6">
                        <select v-model.number="item_workorder.type"  class="form-control"
                                @change="typeChange">
                            <option value="1">Labor Rate</option>
                            <option value="2">Materials Item</option>
                            <option value="3">Expense Item</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Items</label>
                    <div class="col-md-6">
                        <select v-model.number="item_workorder.item_id"  class="form-control" @change="itemChange(item_workorder.item_id)">
                           <option value="">Select</option>
                            <option v-for="item in items" :value="item.id">{{item.name}}</option>
                        </select>
                    </div>
                </div>


                <div class="form-group">
                    <label class="control-label col-md-2">Description</label>
                    <div class="col-md-6">
                        <textarea rows="3" class="form-control" v-model="item_workorder.description"/>
                    </div>
                </div>


                <div class="form-group">
                <label class="control-label col-md-2">Quantity</label>
                <div class="col-md-6">
                    <input class="form-control" v-model="item_workorder.quantity"/>
                </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Date</label>
                    <div class="col-md-6">
                        <input  type="date" class="form-control" v-model="item_workorder.date"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-sm-2">Billable Status</label>
                    <div class="col-sm-5">
                        <label class="radio-inline"><input type="radio" v-model.number="item_workorder.billing_status" value="1"
                                                           @click="item_workorder.billing_status=1">Billable</label>
                        <label class="radio-inline"><input type="radio" v-model.number="item_workorder.billing_status" value="0"
                                                           @click="item_workorder.price=0,item_workorder.cost=0">Non-Billable</label>
                    </div>
                </div>

                <div v-if="item_workorder.billing_status==1">

                <div class="form-group">
                    <label class="control-label col-md-2">Price</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="item_workorder.price"/>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Cost</label>
                    <div class="col-md-6">
                        <input class="form-control" v-model="item_workorder.cost"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="checkbox col-md-6">
                        <label class="control-label col-md-4 checkbox">Taxable status</label>
                        <div class="col-md-4">
                            <label class="control-label"><input type="checkbox" value="1" v-model.number="item_workorder.tax_status"/>
                                Taxable by default</label>
                        </div>
                    </div>
                </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-2">Comments</label>
                    <div class="col-md-6">
                        <textarea rows="3" class="form-control" v-model="item_workorder.comment"/>
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
    import axios from 'axios'

    export default {
        data(){
            return{
                item_workorder:{
                    item_id:'',
                    type: '',description: '',price: 0.00,cost: 0.00,  tax_status: 0,comment:'',
                    date:'',
                    billing_status: 1,
                    quantity:'',
                    work_order_id:this.$route.params.id,
                    assignment_type:this.$route.params.type,
                },
                items:[],


            }
        },
        methods:{
            typeChange(){
                axios.get('item/type/'+this.item_workorder.type).then(response=>{
                    this.items=response.data;
                })
            },

            assignItem(){
                axios.post('assign-item',this.item_workorder).then(response=>{
                    console.log(response.data);
                })

            },
            itemChange(id)
            {
                axios.get('item/'+id).then(response=>{
                    this.item_workorder.price=response.data.price;
                    this.item_workorder.cost= response.data.cost;
                    this.item_workorder.tax_status=response.data.taxable;

                })
            }


        },
        created(){

        },
    }

</script>

<style>

</style>