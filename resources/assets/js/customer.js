/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
//import bootstrap from "./bootstrap";

import Vue from 'vue';
import VueTimepicker from 'vue2-timepicker';
import axios from 'axios';
import VueRouter from 'vue-router';

import Header from './components/Header.vue';
import CustomerHeader from './components/CustomerHeader.vue'

import Customer from './components/Customer.vue';
import Contact from './components/Contact.vue';
import Location from './components/Location.vue';
import CustomerAll from './components/CustomerAll.vue';

import WorkOrder from './components/WorkOrderCreate.vue';
import WorkOrderAll from './components/WorkOrderAll.vue';
import WorkOrderEdit from './components/WorkOrderEdit.vue';
import AddWorkorderItem from './components/AddWorkorderItem.vue';

import RecurringWorkOrder from './components/RecurringWorkOrderCreate.vue';
import RecurringAll from './components/RecurringWorkOrderAll.vue';
import RecurringEdit from './components/RecurringEdit.vue'

import ContactAll from './components/ContactAll.vue';
import LocationAll from './components/LocationAll.vue';
import CustomerEdit from './components/CustomerEdit.vue';
import ContactEdit from './components/ContactEdit.vue';
import LocationEdit from './components/LocationEdit.vue';

import EquipmentAdd from './components/EquipmentCreate.vue';
import EquipmentAll from './components/EquipmentAll.vue';
import EquipmentEdit from './components/EquipmentEdit.vue';

import ContractAll from './components/ContractAll.vue';
import ContractAdd from './components/ContractCreate.vue';
import ContractEdit from './components/ContractEdit.vue';

import ItemAdd from './components/ItemCreate.vue';
import ItemAll from './components/ItemAll.vue';
import ItemEdit from './components/ItemEdit.vue';

import UserAdd from './components/AddUser.vue';
import TaxCodeAdd from './components/AddTaxCode.vue';
import EquipmentTypeAdd from './components/AddEquipmentType.vue';
import ContractTypeAdd from './components/AddContractType.vue';

import AssignTo from './components/AssignTo.vue';

import ChangeStatusWorkOrder from './components/ChangeStatusWorkOrder.vue';
import ChangeStatusRecurringJob from './components/ChangeStatusRecurringJob.vue';
import ChangeStatusContract from './components/ChangeStatusContract.vue';

import CustomerDetails from './components/CustomerDetails.vue';
import CustomerContact from './components/CustomerContact.vue';
import CustomerLocation from './components/CustomerLocation.vue';
import CustomerContract from './components/CustomerContract.vue';

Vue.use(axios);
Vue.use(VueRouter);


axios.interceptors.response.use(function (response) {
    // Do something with response data
   return response;

}, function (error) {
    // Do something with response error
    if(error.response.status === 401)
        window.location.href = "login";

    return Promise.reject(error);
});


const routes = [
    {path:'/new', name:'add-customer', component:Customer},
    {path:'/vue/all', name:'customer-all',component:CustomerAll},
    {path:'/edit/:id',name :'edit',component:CustomerEdit},

    {path:'/customer-contact/add/:id',name:'add-contact',component:Contact},
    {path:'/contact-edit/:id',name:'edit-contact',component:ContactEdit},
    {path:'/customer-location/add/:id',name:'add-location',component:Location},
    {path:'/customer-details/:id',name:'customer-details',component:CustomerDetails},
    {path:'/customer-contact-all/:id',name:'customer-contacts',component:CustomerContact},
    {path:'/customer-location-all/:id',name:'customer-locations',component:CustomerLocation},
    {path:'/customer-contract-all/:id',name:'customer-contracts',component:CustomerContract},

    {path:'/work-order/vue/add', name : 'add-workorder', component:WorkOrder},
    {path:'/work-order/vue/all',name : 'workorder-all', component:WorkOrderAll},
    {path:'/work-order/assign/:id',name:'workorder-assign',component:AssignTo},
    {path:'/work-order/edit/:id',name:'workorder-edit',component:WorkOrderEdit},


    {path:'/recurring-work-order/vue/add', name : 'add-recurring-workorder', component:RecurringWorkOrder},
    {path:'/recurring/all',name:'recurring-all',component:RecurringAll},
    {path:'/recurring-edit/:id',name:'recurring-edit',component:RecurringEdit},

    {path:'/contact-all',name:'all-contact',component:ContactAll},
    {path:'/location-all',name:'all-location',component:LocationAll},
    {path:'/location-edit/:id',name:'edit-location',component:LocationEdit},

    {path:'/equipment/add', name : 'add-equipment', component:EquipmentAdd},
    {path:'/equipment/all', name : 'all-equipment', component:EquipmentAll},
    {path:'/equipment-edit/:id', name : 'edit-equipment', component:EquipmentEdit},

    {path:'/contract/add', name: 'add-contract', component:ContractAdd},
    {path:'/contract/all', name: 'all-contract', component:ContractAll},
    {path:'/contract/edit/:id',name: 'edit-contract', component:ContractEdit},

    {path:'/item/add', name: 'add-item', component:ItemAdd},
    {path:'/item/all', name: 'all-item', component:ItemAll},

    {path:'/user/add', name: 'add-user', component:UserAdd},

    {path:'/taxcode/add', name: 'add-taxcode', component:TaxCodeAdd},

    {path:'/equipment-type/add', name: 'add-equipment-type', component:EquipmentTypeAdd},

    {path:'/contract-type/add', name: 'add-contract-type', component:ContractTypeAdd},

    {path:'/item-assign/:id/:type',name:'add-workorder-item',component:AddWorkorderItem},
    {path:'/item/edit/:id', name: 'edit-item', component:ItemEdit},

    {path:'/status-workorder', name: 'change-status-workorder', component:ChangeStatusWorkOrder},
    {path:'/status-recurring', name: 'change-status-recurringjob', component:ChangeStatusRecurringJob},
    {path:'/status-contract', name: 'change-status-contract', component:ChangeStatusContract},
];

const router =new VueRouter({
   routes: routes
});


const app=new Vue({
   el:'#app',
   router,
    components:{
        customerHeader:Header,
        customerDetailsHeader: CustomerHeader,
    }
});
