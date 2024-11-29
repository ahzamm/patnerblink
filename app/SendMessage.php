<?php

namespace App;


class SendMessage
{
    public static function smsSend($number,$text)
    {
        $client = new \GuzzleHttp\Client();

        // $res = $client->request('GET', "https://pk.eocean.us/APIManagement/API/RequestAPI?user=logon_eocean&pwd=AOeSiZ1udKY2pxhD6MpPErkc8x11fOmGDQyQ565GMNwv8KC8vQQhth%2b%2fF0Sj%2buS09w%3d%3d&sender=Logon&reciever=$number&msg-data=$text&response=string");
        $res = $client->request('GET', "https://pk.eocean.us/APIManagement/API/RequestAPI?user=logon_eocean&pwd=AHxZnVT%2bBVEWxMbcYWpXrDmQPVSh0j1VHOlDNKk8noYbZTykx9BertRppilaadH5Bw%3d%3d&sender=Logon&reciever=$number&msg-data=$text&response=string");

         // $res = $client->request('GET', 'http://110.93.218.36:24555/api?action=sendmessage&username=logon&password=uyyhVfde&recipient='.$number.'&originator=99095&messagedata='.$text.'');

         // $res = $client->request('GET', 'http://110.93.218.36:24555/api?action=sendmessage&username=logon&password=uyyhVfde&recipient='.$number.'&originator=99095&messagedata='.$text);


        if($res->getStatusCode()==200)
        {
            return true;
        }
        else{
           return false;
        }
    }
}
