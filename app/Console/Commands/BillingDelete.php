<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\model\Users\UserInfo;
use App\model\Users\AmountTransactions;
use App\model\Users\PaymentsTransactions;
use App\model\Users\LedgerReport;
use App\model\Users\UserAmount;
use App\model\Users\AmountBillingInvoice;

class BillingDelete extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will delete records';

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
     * @return int
     */
    public function handle()
    {
        $date=date('Y-m-d');
        $today = date('d',strtotime(date($date)));
        if($today >= 10 && $today < 25){
            $start=date("Y-m-25", strtotime("-1 month", strtotime($date)));
            $end=date('Y-m-10');
        }else{
            $start=date('Y-m-10');
            $end=date('Y-m-25');
        }
        // 
        //
        if($today == 10 || $today == 25){
            $this->generateCommision('dealer',$start,$end);
            $this->generateCommisionsubdealer('subdealer',$start,$end);   
        }
        
    }
    public function generateCommision($status,$start,$end){

        $data = UserInfo::where('status',$status)->get();
        //
        foreach ($data as  $value) {
        //
          $dealerid = $value['dealerid'];
          $resellerid = $value['resellerid'];
          $manager_id = $value['manager_id'];
          //
          $margin_sum = AmountBillingInvoice::where('charge_on','>',$start.' 12:00:00')->where('charge_on','<',$end.' 12:00:00')->where('dealerid',$dealerid)->where('sub_dealer_id',NULL)->sum('margin');
          //
          if($margin_sum > 0){
            // amount transaction
            $transactionAmount = new AmountTransactions();
            $transactionAmount->receiver = $resellerid;
            //
            $transactionAmount->sender = $manager_id; 
            //
            $transactionAmount->amount = 0;
            $transactionAmount->cash_amount = 0;
            $transactionAmount->last_remaining_amount = 0;
            $transactionAmount->date = Date('Y-m-d H:i:s');

            $transactionAmount->action_by_user = "System";       
            $transactionAmount->action_ip = "192.168.10.10";
            $transactionAmount->comments = "Billing for the period".$start."to".$end;
            $transactionAmount->commision = 'yes';
            $transactionAmount->com_amount = $margin_sum;
            $transactionAmount->margin_from = $dealerid;
            $transactionAmount->save();

            //    ////
            $userAmount = UserAmount::where('username',$resellerid)->first();
            $amount = $userAmount->amount;
            $balance = $amount + $margin_sum;
            $userAmount->amount = $balance;
            $userAmount->save();
        }

    }
}


public function generateCommisionsubdealer($status,$start,$end){

    $data = UserInfo::where('status',$status)->get();
        //
    foreach ($data as  $value) {
        //
      $dealerid = $value['dealerid'];
      $resellerid = $value['resellerid'];
      $manager_id = $value['manager_id'];
      $sub_dealer_id = $value['sub_dealer_id'];
          //
      $margin_sum = AmountBillingInvoice::where('charge_on','>',$start.' 12:00:00')->where('charge_on','<',$end.' 12:00:00')->where('dealerid',$dealerid)->where('sub_dealer_id',$sub_dealer_id)->sum('margin');

          //
      if($margin_sum > 0){
        // amount transaction
        $transactionAmount = new AmountTransactions();
        $transactionAmount->receiver = $dealerid;
            //
        $transactionAmount->sender = $resellerid; 
            //
        $transactionAmount->amount = 0;
        $transactionAmount->cash_amount = 0;
        $transactionAmount->last_remaining_amount = 0;
        $transactionAmount->date = Date('Y-m-d H:i:s');

        $transactionAmount->action_by_user = "System";       
        $transactionAmount->action_ip = "192.168.10.10";
        $transactionAmount->comments = "Billing for the period".$start."to".$end;
        $transactionAmount->commision = 'yes';
        $transactionAmount->com_amount = $margin_sum;
        $transactionAmount->margin_from = $sub_dealer_id;
        $transactionAmount->save();

       //    ////
            $userAmount = UserAmount::where('username',$dealerid)->first();
            $amount = $userAmount->amount;
            $balance = $amount + $margin_sum;
            $userAmount->amount = $balance;
            $userAmount->save();
    }

}
}








}
