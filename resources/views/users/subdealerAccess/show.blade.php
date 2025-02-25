<div style="overflow-wrap: auto">
    <table class="table table-condensed" style="text-align: center;">
        <thead>
            <tr>
                <th style="text-align: center; font-size:18px;">
                    Serial#
                </th>
                <th style="text-align: center; font-size:18px;">
                    Menu
                </th>
                <th style="text-align: center; font-size:18px;">
                    Sub Menu
                </th>
                <th style="text-align: center; font-size:18px;">
                    Access
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach($userAccesses as $key => $userAccess)
            <tr>
                <td>
                    {{$key+1}}
                </td>
                <td style="font-weight: bold; text-align: left !important; ">
                    @php
                    $menu =  App\model\Users\Menu::where('id',$userAccess->menu_id)->first();
                    @endphp
                    {{$menu->menu}}
                </td>
                <td style="font-weight: bold; text-align: left !important; ">
                    {{$userAccess->submenu}}
                </td>
                <td>
                    @if($userAccess->status == 1)
                    <div style="float: left; width: 100%;">
                        <p><input type="checkbox"  class="lcs_check" checked data-value="{{$userAccess->id}}" data-id="{{$userAccess->sbID}}"  autocomplete="off" /></p>
                    </div>
                    @else
                    <div style="float: left; width: 100%;">
                        <p><input type="checkbox"  class="lcs_check" data-id="{{$userAccess->sbID}}"  data-value="{{$userAccess->id}}" autocomplete="off" /></p>
                    </div>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>