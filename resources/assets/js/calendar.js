import Vue from 'vue';
import VueRouter from 'vue-router';


import CalendarHeader from './components/CalendarHeader.vue';
import AddAppointment from './components/AddAppointment.vue';
import AppointmentEdit from './components/AppointmentEdit.vue';
import CalendarRaw from './components/CalendarRaw.vue';
import WeekCalendar from './components/WeekCalendar.vue';
import DayCalendar from './components/DayCalendar.vue';
Vue.use(VueRouter);


const routes=[

    {path:'/calendar-view', name: 'calendar-view' , component:CalendarRaw},
    {path:'/week-calendar',name:'week-calendar',component:WeekCalendar},
    {path:'/day-calendar',name:'day-calendar',component:DayCalendar},
    {path: '/add-appointment/:date', name:'add-appointment', component:AddAppointment},
    {path: 'appointment/edit/:id', name:'appointment-edit', component:AppointmentEdit},
];

const router =new VueRouter({
    routes: routes
});



// Vue2
 const app2 = new Vue({
    el : '#app2',
     router,
    components : {
       calendarHeader: CalendarHeader,
        calendar:CalendarRaw
    }
})


