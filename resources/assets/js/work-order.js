/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
//import bootstrap from "./bootstrap";

import Vue from 'vue'
import axios from 'axios'
import WorkOrderAll from './components/WorkOrderAll.vue'
import WorkOrderAdd from './components/WorkOrderCreate.vue'
Vue.use(axios);

const app = new Vue({
    el: '#allWorkOrder',
    components:{
        workOrderAll: WorkOrderAll
    }
})

const app2 = new Vue({
    el: '#addWorkOrder',
    components:{
        workOrderAdd: WorkOrderAdd
    }
})
