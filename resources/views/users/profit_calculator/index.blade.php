<!--
* This file is part of the SQUADCLOUD project.
*
* (c) SQUADCLOUD TEAM
*
* This file contains the configuration settings for the application.
* It includes database connection details, API keys, and other sensitive information.
*
* IMPORTANT: DO NOT MODIFY THIS FILE UNLESS YOU ARE AN AUTHORIZED DEVELOPER.
* Changes made to this file may cause unexpected behavior in the application.
*
* WARNING: DO NOT SHARE THIS FILE WITH ANYONE OR UPLOAD IT TO A PUBLIC REPOSITORY.
*
* Website: https://squadcloud.co
* Created: September, 2024
* Last Updated: 01th September, 2024
* Author: SquadCloud Team <info@squadcloud.co>
*-->
<!-- Code Onset -->
@extends('users.layouts.app')
@section('title') Dashboard @endsection
@section('owncss')
<style type="text/css">
   .ttable{
      overflow: hidden;
      overflow-y: scroll;
      max-height: 350px;
      width: 100%;
   }
   .percent_symbol{
      font-weight:bold;
      padding: 0 20px;
      position: absolute;
      right: 15px;
      bottom: 6px;
   }
   .calculate_table tbody tr:not(:first-child) td{
      border-top: 1px solid #0d4dab;
      vertical-align: middle;
   }
</style>
@endsection
@section('content')
<div class="page-container row-fluid container-fluid">
   <!-- CONTENT START -->
   <section id="main-content" class=" ">
      @include('users.profit_calculator.import-profit-calculator')
   </section>
   <!-- CONTENT END -->
</div>
@endsection

<!-- Code Finalize -->