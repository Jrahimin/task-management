<?php

namespace App\Console\Commands;

use App\Enumeration\RecurrenceFrequency;
use App\Enumeration\WorkOrderStatus;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RecurringJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:recurring';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Adds work orders according to schedule';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //taking present date, time, time range, day, month
        $presentDate = Carbon::now()->format('Y-m-d');
        $presentTime = Carbon::now()->subMinutes(5)->format('H:i');
        $lastTime = Carbon::now()->addMinutes(5)->format('H:i');
        $presentDay = Carbon::now()->format('D');
        $presentDayOfMonth=Carbon::now()->format('j');
        $presentPartOfMonth=Carbon::now()->weekOfMonth;

        //filtering recurring jobs. Checking if jobs within date range and time
        $recurringWorkOrders = \App\Model\RecurringWorkOrder::where('start_date','<=', $presentDate)->where('end_date','>=',$presentDate)
            ->whereBetween('time',[$presentTime, $lastTime])
            ->where('status', '<>', WorkOrderStatus::$CLOSED)->get();


        //for each filter recurring job, work order will be added accordingly
        foreach ($recurringWorkOrders as $recurringWorkOrder)
        {
            $firstWorkOrder = false;
            $workOrderFromRecurringJob = new \App\Model\WorkOrder();
            $lastWorkOrder = \App\Model\WorkOrder::where('recurring_work_order_id', $recurringWorkOrder->id)
                ->orderBy('id', 'Desc')->first();

            if($lastWorkOrder==null)
            {
                $lastWorkDateTime = new Carbon($recurringWorkOrder->start_date);

                $lastWorkDate=$lastWorkDateTime->format('Y-m-d');


                if($presentDate!=$lastWorkDate)
                    continue;

                $firstWorkOrder = true;
            }
            else
                $lastWorkDateTime = new Carbon($lastWorkOrder->created_at);

            if($recurringWorkOrder->frequency==RecurrenceFrequency::$ONCE)
            {
                //create work order
                $workOrderFromRecurringJob->createWorkOrderFromRecurringJob($recurringWorkOrder);
                $recurringWorkOrder->no_of_occurance=$recurringWorkOrder->no_of_occurance+1;
                $recurringWorkOrder->save();

            }


            if($recurringWorkOrder->frequency==RecurrenceFrequency::$DAILY)
            {
                if(!$firstWorkOrder)
                {
                    $scheduleDateTime = $lastWorkDateTime->addDays($recurringWorkOrder->interval);
                    $scheduleDate = $scheduleDateTime->format('Y-m-d');

                    if($presentDate!=$scheduleDate)
                        continue;
                }


                //create work order
                $workOrderFromRecurringJob->createWorkOrderFromRecurringJob($recurringWorkOrder);
                $recurringWorkOrder->no_of_occurance=$recurringWorkOrder->no_of_occurance+1;
                $recurringWorkOrder->save();
            }

            if($recurringWorkOrder->frequency==RecurrenceFrequency::$WEEKLY)
            {
                $days_in_week = explode(',', $recurringWorkOrder->days_in_week);
                if(!$firstWorkOrder)
                {
                    $interval = $recurringWorkOrder->interval * 7;

                    $scheduleDateTime = $lastWorkDateTime->addDays($interval);
                    $scheduleDate = $scheduleDateTime->format('Y-m-d');

                    if($presentDate!=$scheduleDate)
                        continue;
                }

                if(in_array($presentDay, $days_in_week))
                {
                    //create work order
                    $workOrderFromRecurringJob->createWorkOrderFromRecurringJob($recurringWorkOrder);
                    $recurringWorkOrder->no_of_occurance=$recurringWorkOrder->no_of_occurance+1;
                    $recurringWorkOrder->save();
                }
            }


            if($recurringWorkOrder->frequency==RecurrenceFrequency::$MONTHLY)
            {
                if(!$firstWorkOrder)
                {
                    $scheduleDateTime = $lastWorkDateTime->addMonth($recurringWorkOrder->interval);
                    $scheduleDate = $scheduleDateTime->format('Y-m-d');

                    if($presentDate!=$scheduleDate)
                        continue;
                }

                if(!empty($recurringWorkOrder->day_in_month))
                {
                    if($presentDayOfMonth==$recurringWorkOrder->day_in_month)
                    {
                        //create work order
                        $workOrderFromRecurringJob->createWorkOrderFromRecurringJob($recurringWorkOrder);
                        $recurringWorkOrder->no_of_occurance=$recurringWorkOrder->no_of_occurance+1;
                        $recurringWorkOrder->save();
                    }
                }
                else
                {
                    if($presentDay==$recurringWorkOrder->week_in_month && $presentPartOfMonth==$recurringWorkOrder->part_of_month)
                    {
                        //create work order
                        $workOrderFromRecurringJob->createWorkOrderFromRecurringJob($recurringWorkOrder);
                        $recurringWorkOrder->no_of_occurance=$recurringWorkOrder->no_of_occurance+1;
                        $recurringWorkOrder->save();

                    }
                }
            }

            if(!empty($recurringWorkOrder->end_occurance)&& $recurringWorkOrder->end_occurance==$recurringWorkOrder->no_of_occurance)
            {
                $recurringWorkOrder->status=WorkOrderStatus::$CLOSED;
                $recurringWorkOrder->save();
            }

        }
    }
}
