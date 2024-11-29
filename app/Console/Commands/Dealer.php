<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\model\Users\UserInfo;
use App\model\Users\AmountTransactions;
use App\model\Users\PaymentsTransactions;
use App\model\Users\LedgerReport;
use App\model\Users\UserAmount;
use App\model\Users\AmountBillingInvoice;

class Dealer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:dealer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'commision for reseller';

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
        $start=date('2020-04-10');
        $end=date('2020-04-25');
        $userReseller = UserInfo::where('status','dealer')->first();
        $status = $userReseller['status'];
        $this->generate($status,$start,$end);

    }
    public function generate($status,$start,$end){

        $data = UserInfo::where('status',$status)->get();
       foreach ($data as  $value) {
        $dealerid = $value['dealerid'];
        $resellerid = $value['resellerid'];
        $manager_id = $value['manager_id'];
     
        $dealersum = AmountBillingInvoice::where('charge_on','>',$start.' 12:00:00')->where('charge_on','<',$end.' 12:00:00')->where('dealerid',$dealerid)->where('resellerid',$resellerid)->sum('d_acc_rate');
        $resellersum = AmountBillingInvoice::where('charge_on','>',$start.' 12:00:00')->where('charge_on','<',$end.' 12:00:00')->where('dealerid',$dealerid)->where('resellerid',$resellerid)->sum('r_acc_rate');
     
        
       $userdealer1 = UserInfo::where('status','reseller')->where('resellerid',$resellerid)->first();

       
           $profit = $dealersum - $resellersum;

         
// amount transaction
          $transactionAmount = new AmountTransactions();
          $transactionAmount->receiver = $userdealer1['username'];
         
          $transactionAmount->sender = "logonbroadband"; //username will be store
                  
          $transactionAmount->amount = 0;
          $transactionAmount->cash_amount = 0;
          $transactionAmount->last_remaining_amount = 0;
          $transactionAmount->date = Date('Y-m-d H:i:s');
         
          $transactionAmount->action_by_user = "System"; // here sender is admin      
          $transactionAmount->action_ip = "192.168.10.10";
          $transactionAmount->comments = "Billing for the period".$start."to".$end.' '.$value->username;
          $transactionAmount->commision = 'yes';
          $transactionAmount->com_amount = $profit;
          $transactionAmount->save();

        ////
        $userAmount = UserAmount::where('username',$userdealer1['username'])->first();
        $amount = $userAmount->amount;
        $balance = $amount + $profit;
        
        $userAmount->amount = $balance;

        $userAmount->save();
       }

    }
}
