<style>
  .slider:before{
    height: 15px;
    left: 2px;
    bottom: 3px;
  }
  input:checked + .slider:before{
    transform: translateX(16px);
  }
</style>
<table class="table table-condensed dt-responsive display w-100" style="text-align: center;" id="allowAccessTable">
  <thead>
    <tr>
      <th>Serial#</th>
      <th style="text-align:left">Menu</th>
      <th style="text-align:left">Sub Menu</th>
      <th>Access</th>
    </tr>
  </thead>
  <tbody>
    @php 
    $num = 1;
    @endphp
    @foreach($userAccesses as $key => $userAccess)
    <tr>
      <td>
        {{$num}}
      </td>
      <td style="text-align: left !important; ">
        @php
        $menu =  App\model\Users\Menu::where('id',$userAccess->menu_id)->first();
        @endphp
        {{$menu->menu}}
      </td>
      <td style="text-align: left !important; ">
        {{$userAccess->submenu}}
      </td>
      <td>
        <?php
        $check = NULL; $status = 0;
        if(in_array($userAccess->sbID, $allow)){
          $check = 'checked'; 
          $status = 1;
        }
        ?>
        <div style="float: left; width: 100%;">
          <label class="switch" style="width: 46px;height: 20px;">
            <input type="checkbox" class="lcss_check"  {{$check}} data-value="{{$userAccess->id}}" data-id="{{$userAccess->sbID}}" user-id="{{$id}}" status="{{$status}}">
            <span class="slider square" ></span>
          </label>
        </div>
      </td>
    </tr>
    @php $num++; @endphp
    @endforeach
  </tbody>
</table>