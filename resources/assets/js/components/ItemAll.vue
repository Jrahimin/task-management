<template>
    <div>
        <form class="form-horizontal">
        <div class="filter-box">
            <div class="form-group">
                <div class="col-md-12 text-right">
                    <button @click="toItem" class="btn btn-primary"><span class=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Item</span></button>
                </div>
            </div>
        </div>

            <div class="panel panel-info">
                <div class="panel-heading">Search</div>
                <div class="panel-body">
            <div class="form-group">
                <div class="form-group col-md-4">
                    <label class="control-label col-md-4">Type</label>
                    <div class="col-md-8">
                        <select class="form-control" v-model="search.type" @change="doSearch">
                            <option value="">Type</option>
                            <option value="1">Labor</option>
                            <option value="2">Material</option>
                            <option value="3">Expense</option>
                        </select>
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label class="control-label col-md-4">Item name</label>
                    <div class="col-md-8">
                        <input class="form-control" v-model="search.name" @keyup="doSearch"/>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <div class="form-group col-md-4">
                    <label class="control-label col-md-4">Manufacturer</label>
                    <div class="col-md-8">
                        <input class="form-control" v-model="search.manufacturer" @keyup="doSearch"/>
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label class="control-label col-md-4">Item No</label>
                    <div class="col-md-8">
                        <input class="form-control" v-model="search.item_no" @keyup="doSearch"/>
                    </div>
                </div>

                <div class="form-group col-md-2">
                    <div class="checkbox">
                        <label><input type="checkbox"  v-model="search.is_taxable" @change="doSearch">Taxable</label>
                        <!--<input type="hidden" v-model="search.is_taxable" value="false">-->
                    </div>
                </div>
            </div>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">Item List</div>
                <div class="panel-body">
        <table class="table table-striped text-center">
            <thead>
            <tr>
                <th>Item Type</th>
                <th>Name</th>
                <th>Description</th>
                <th>Manufacturer</th>
                <th>MFR. Item No</th>
                <th>Price</th>
                <th>Cost</th>
                <th>Taxable</th>
                <th></th>
                <th></th>
            </tr>
            </thead>

            <tbody>

            <tr v-for="item in items">
                <td v-if="item.type==1">Labor</td>
                <td v-if="item.type==2">Material</td>
                <td v-if="item.type==3">Expense</td>
                <td>{{ item.name }}</td>
                <td>{{ item.description }}</td>
                <td>{{ item.manufacturer }}</td>
                <td>{{ item.mfr_item_no }}</td>
                <td>{{ item.price }}</td>
                <td>{{ item.cost }}</td>
                <td v-if="item.taxable==1">Taxable</td>
                <td v-else>Not Taxable</td>
                <td>
                    <router-link :to="{name:'edit-item',params:{id:item.id}}" tag="button" class="btn btn-primary">Edit</router-link>
                </td>
                <td>
                    <a class="btn btn-danger" @click="isDelete=true, itemId=item.id">Delete</a>
                </td>
            </tr>
            </tbody>
        </table>
                    <div v-if="isDelete" class="modal-dialog" role="document" style="position: fixed; top:2%; left: 0; right: 0;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" aria-label="Close" @click="isDelete=false"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title">Delete</h4>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure want to delete?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal" @click="isDelete=false">Cancel</button>
                                <button id="btnDelete" type="button" class="btn btn-danger" @click="deleteItem(itemId)">Delete</button>
                            </div>
                        </div>
                    </div>
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
                items: [], itemsCopy: [],
                search:{
                    manufacturer: '',
                    type: '',
                    item_no: '',
                    name: '',
                    is_taxable: 0,
                },
                isDelete:false,
                itemId:''
            }
        },
        created() {
            axios.get('item/all').then(response => {
                this.items = response.data;
                this.itemsCopy = response.data;
            });
        },
        methods:{
            toItem(){
                this.$router.push({name:'add-item'});
            },
            doSearch(){
                let that = this;
                this.items = this.itemsCopy;

                if(this.search.type){
                    this.items = this.items.filter(function (item) {
                        if(item.type == that.search.type){
                            return item;
                        }
                    })
                }
                if(this.search.name){
                    this.items = this.items.filter(function (item) {
                        if(item.name.toLowerCase().includes(that.search.name.toLowerCase())){
                            return item;
                        }
                    })
                }
                if(this.search.manufacturer){
                    this.items = this.items.filter(function (item) {
                        if(item.manufacturer){
                            if(item.manufacturer.toLowerCase().includes(that.search.manufacturer.toLowerCase())){
                                return item;
                            }
                        }
                    })
                }
                if(this.search.item_no){
                    this.items = this.items.filter(function (item) {
                        if(item.mfr_item_no){
                            if(item.mfr_item_no.toLowerCase().includes(that.search.item_no.toLowerCase())){
                                return item;
                            }
                        }
                    })
                }
                if(this.search.is_taxable){
                    console.log(this.search.is_taxable)
                    this.items = this.items.filter(function (item) {
                        if(item.taxable == 1){
                            return item;
                        }
                    })
                }
            },

            deleteItem(itemId)
            {
                axios.get('item/delete/'+itemId).then(response=>{
                    this.$router.go();
                })
            }
        }
    }
</script>

<style>

</style>