<template>

    <div class="container">
        <fieldset>
            <legend><h3>New Equipment Type</h3></legend>
            <form class="form-horizontal" @submit.prevent="addEquipmentType">

                <div class="form-group">
                    <label class="control-label col-md-2">Type Name</label>
                    <div class="col-md-4">
                        <input class="form-control" v-model="equipment.name"/>
                    </div>
                </div>

                <div class="form-group alert alert-danger" v-if="errors!='' && errors!=undefined">
                    <ul>
                        <li v-for="error in errors">{{ error }}</li>
                    </ul>
                </div>

                <div class="col-md-7 pull-right">
                    <button class="btn btn-primary">Save</button>
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
                equipment:{
                    name: '',
                },
                errors: [],
            }
        },
        methods:{
            addEquipmentType(){
                axios.post('equipment-type/add', this.equipment).then(response =>{
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