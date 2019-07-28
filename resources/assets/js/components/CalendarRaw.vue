<template>
    <div id='app'>

        <div class="filter-box">
            <div class="row">
                <div class="col-md-12 text-right">
                    <button @click="toAppointment" class="btn btn-primary"><span class=""><i class="fa fa-plus-circle" aria-hidden="true"></i> Add Appointment</span></button>
                </div>
            </div>
        </div>

        <div class='calendar'>
            <div class='header'>
                <a class='arrow' @click='movePreviousYear'>Previous Year</a>
                <a class='arrow' @click='movePreviousMonth'>Previous Month </a>
                <span class='title' @click='moveThisMonth'>
        {{ header.label }}
      </span>
                <a class='arrow' @click='moveNextMonth'>Next Month</a>
                <a class='arrow' @click='moveNextYear'>Next Year</a>
            </div>
            <div class='weekdays'>
                <div class='weekday' v-for='weekday in weekdays'>
                    {{ weekday.label }}
                </div>
            </div>
            <div class='week' v-for='week in weeks'>
                <div class='day' :class='{ today: day.isToday }' v-for='day in week' @click="addAppointment(day.date)">
                    {{ day.label }}
                    <div class="box-info" v-for="event in day.events" @click=" showEventDetails" :id="event.id">
                        <ul id="dayUl" style="display: block; width: 100%">
                            <li><b>{{event.subject}}</b></li>
                            <li>{{event.customer}}</li>
                            <li>{{event.notes}}</li>
                        </ul>
                    </div>

                </div>
            </div>

        </div>
        <div v-if="newAppointment" class="modal-content">

            <add-appointment :date="currentDate" ></add-appointment>


                <button type="button" class="btn btn-default" data-dismiss="modal"  @click="hideModal">Close</button>


        </div>
        <div v-if="eventDetail" class="modal-content" style="width:40%; position: fixed; top:2%; left: 0; right: 0;">

                <div class="modal-header">
                    <h4 class="modal-title">{{event.subject}}</h4>
                </div>
                <div class="modal-body">
                    <p>{{event.start_date}}</p>
                    <p>{{event.end_date}}</p>
                    <p>{{event.schedule}}</p>
                    <p>{{event.is_all_day}}</p>
                    <p>{{event.notes}}</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" @click="hideModal">Close</button>
                    <button type="button" class="btn btn-info" @click="editEvent(event.id)">Edit</button>
                    <button type="button" class="btn btn-danger" @click="isDelete=true,eventId=event.id">Delete</button>
                </div>
        </div>
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
                    <button id="btnDelete" type="button" class="btn btn-danger" @click="deleteEvent(eventId)">Delete</button>
                </div>
            </div>
        </div>

    </div>
</template>

<script>
    import axios from 'axios';
    import moment from 'moment';
    import AddAppointment from './AddAppointment.vue';
    const date=new Date();
    const _todayComps = {
        year: date.getFullYear(),
        month: date.getMonth() + 1,
        day: date.getDate()
    };
    const daysInMonths = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
    const monthLabels = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
    const weekdayLabels = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
    const monthLength = 0;
    const monthCasing = 'title';
    const weekdayLength = 3;
    const weekdayCasing = 'title';

    // Helper function for label transformation
    const transformLabel = (label, length, casing) => {
        label = length <= 0 ? label : label.substring(0, length);
        if (casing.toLowerCase() === 'lower') return label.toLowerCase();
        if (casing.toLowerCase() === 'upper') return label.toUpperCase();
        return label;
    };
    export default {
        data() {
            return {

                  month: date.getMonth()+1,
                  year: date.getFullYear(),
                  events:[],
                  newAppointment:false,
                  currentDate:'',
                  event:{},
                  eventDetail:false,
                  isDelete:false,
                  eventId:''

            }
        },
        computed :{

            monthIndex() {
                return this.month - 1;
            },
            months() {
                return monthLabels.map((ml, i) => ({
                    label: transformLabel(ml, monthLength, monthCasing),
                    number: i + 1,
                }));
            },
            // State for weekday header (no dependencies yet...)
            weekdays() {
                return weekdayLabels.map((wl, i) => {
                    return {
                        label: transformLabel(wl, weekdayLength, weekdayCasing),
                        number: i + 1,
                    };
                });
            },

            header() {
                const month = this.months[this.monthIndex];
                return {
                    month,
                    year: this.year.toString(),
                    shortYear: this.year.toString().substring(2, 4),
                    label: month.label + ' ' + this.year,
                };
            },

            // Returns number for first weekday (1-7), starting from Sunday
            firstWeekdayInMonth() {
                return new Date(this.year, this.monthIndex, 1).getDay() + 1;
            },
            daysInMonth() {
                // Check for February in a leap year
                const isFebruary = this.month === 2;
                const isLeapYear = (this.year % 4 == 0 && this.year % 100 != 0) || this.year % 400 == 0;
                if (isFebruary && isLeapYear) return 29;
                // ...Just a normal month
                return daysInMonths[this.monthIndex];
            },
            weeks() {
                const weeks = [];
                let monthStarted = false, monthEnded = false;
                let monthDay = 0;
                // Cycle through each week of the month, up to 6 total
                for (let w = 1; w <= 6 && !monthEnded; w++) {
                    // Cycle through each weekday
                    const week = [];
                    for (let d = 1; d <= 7; d++) {
                        // We need to know when to start counting actual month days
                        if (!monthStarted && d >= this.firstWeekdayInMonth) {
                            // Initialize day counter
                            monthDay = 1;
                            // ...and flag we're tracking actual month days
                            monthStarted = true;
                            // Still in the middle of the month (hasn't ended yet)
                        } else if (monthStarted && !monthEnded) {
                            // Increment the day counter
                            monthDay += 1;
                        }

                        // Append day info for the current week

                        week.push({
                            label: monthDay ? monthDay.toString() : '',
                            number: monthDay,
                            weekdayNumber: d,
                            weekNumber: w,
                            beforeMonth: !monthStarted,
                            afterMonth: monthEnded,
                            inMonth: monthStarted && !monthEnded,
                            isToday: monthDay === _todayComps.day && this.month === _todayComps.month && this.year === _todayComps.year,
                            isFirstDay: monthDay === 1,
                            isLastDay: monthDay === this.daysInMonth,
                             events: this.getEvents(new Date(this.year, this.monthIndex, monthDay)),
                            date:new Date(this.year, this.monthIndex, monthDay)


                        });

                        // Trigger end of month on the last day
                        if (monthStarted && !monthEnded && monthDay >= this.daysInMonth) {
                            monthDay = 0;
                            monthEnded = true;
                        }
                    }
                    // Append week info for the month
                    weeks.push(week);
                }
                return weeks;
            },

        },
        methods: {
            moveThisMonth() {
                this.month = _todayComps.month;
                this.year = _todayComps.year;
            },
            moveNextMonth() {
                if (this.month < 12) {
                    this.month++;
                } else {
                    this.month = 1;
                    this.year++;
                }
            },
            movePreviousMonth() {
                if (this.month > 1) {
                    this.month--;
                } else {
                    this.month = 12;
                    this.year--;
                }
            },
            moveNextYear() {
                this.year++;
            },
            movePreviousYear() {
                this.year--;
            },
            getEvents(date) {
                let todaysEvents = [];
                let today = moment(String(date)).format('YYYY-MM-DD');

               todaysEvents=this.events.filter(function(event){
                   if(event.start_date==today||event.end_date==today || (event.start_date<today&&event.end_date>today))
                       return event;
               })

                 return todaysEvents;
            },

            addAppointment(date){
                this.currentDate=moment(String(date)).format('YYYY-MM-DD');
                this.newAppointment=true;
            },
            hideModal()
            {
                this.newAppointment=false;
                this.eventDetail=false;

            },
            showEventDetails(e)
            {
                e.stopPropagation();
                let id=(e.currentTarget.id);
                axios.get('event/'+id).then(response=>{
                    this.event=response.data;
                })
               this.eventDetail=true;
            },
            editEvent(id)
            {
                this.$router.push({name:'appointment-edit',params:{id:id}});
            },
            toAppointment() {
                this.$router.push({name: 'add-appointment'});
            },
            deleteEvent(id)
            {

                axios.get('event-delete/'+id).then(response=>{
                    if(response.data.id==id)
                    {
                        this.eventDetail = false;
                        this.isDelete=false;
                       this.getAllEvents();
                    }
                })
            },
            getAllEvents()
            {
                axios.get('events').then(response=>{
                    this.events=response.data;
                })
            }
        },
        components : {
            AddAppointment
        },
        created(){
            this.getAllEvents();
        }
    }
</script>

<style>
    table{
        table-layout: fixed;
    }
    .calendar{
        display: flex;
        flex-direction: column;
    }
    .header {
        display: flex;
        justify-content: stretch;
        align-items: center;
        color: white;
        padding: 0.5rem 1rem;
        border-width: 1px;
        border-style: solid;
        border-color: #aaaaaa;
        background-color: #133d55;
    }
    .arrow{
    padding: 0 0.4em 0.2em 0.4em;
    font-size: 1.8rem;
    font-weight: 500;
    user-select: none;
    flex-grow: 0;
    }
    .title
    {
    flex-grow: 1;
    font-size: 1.2rem;
    text-align: center;
    }
    .weekdays
    {
        display: flex;
        flex: auto;
    }
    .weekday
    {
        width: 14.2857%;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 0.4rem 0;
        color: #7a7a7a;
        border-width: 1px;
        border-style:  solid;
        border-color:  #aaaaaa;
        background-color:#eaeaea;
    }
    .week{
        display: flex;
    }
    .day{
        width: 14.2857%;
        height: auto;
        display: block;
        justify-content: center;
        align-items: center;
        color: #3a3a3a;
        background-color: whitesmoke;
        border: solid 1px #aaaaaa;
    }
    .today{
        font-weight: 500;
        color: black;
        background-color: #b2dba1;
    }
    #dayUl{
        border-top: 1px #b6a338 solid;
        background-color: lightgoldenrodyellow;
        padding: 3%;
        margin: 2%;
    }
</style>