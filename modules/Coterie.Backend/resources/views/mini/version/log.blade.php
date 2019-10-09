<div class="tabs-container">
    <ul class="nav nav-tabs">

        <li class="active">
            <a data-toggle="tab" href="#tab-1" aria-expanded="true">发布记录</a>
        </li>
    </ul>
    <div class="tab-content">
        <div id="tab-1" class="tab-pane active">
            <div class="panel-body">

                <table class="table table-striped table-bordered table-hover">
                    <thead>
                    <tr>
                        {{--<th>审核编号</th>--}}
                        <th>提交审核时间</th>
                        <th>状态</th>
                        <th>描述</th>
                        <th>版本</th>
                        <th>备注</th>
                        <th>发布时间</th>
                    </tr>
                    </thead>
                    <tbody>

                      @if(isset($lists->data))

                        @foreach ($lists->data as $item)
                            <tr>

                                {{--<td>{{$item->auditid}}</td>--}}

                                <td>{{$item->audit_time}}</td>

                                <?php $template = json_decode($item->template)?>

                                <td>
                                    @if($item->status==0)
                                        审核成功
                                    @elseif($item->status==1)
                                        审核失败
                                    @elseif($item->status==2)
                                        待审核
                                    @elseif($item->status==3)
                                        已发布
                                    @elseif($item->status==4)
                                        审核撤回
                                    @elseif($item->status==5)
                                        取消发布
                                    @endif
                                </td>


                                <td>{{$template->user_desc}}</td>

                                <td>{{$template->user_version}}</td>

                                <td>

                                    @if($item->reason)
                                        {{--备注：{{$item->note}}--}}
                                        审核失败原因:{{$item->reason}}
                                    @else
                                        {{$item->note}}
                                    @endif

                                </td>

                                <td>
                                    @if($item->status==3)
                                        {{$item->release_time}}
                                    @endif
                                </td>

                            </tr>
                        @endforeach

                      @endif

                    </tbody>
                </table>

                <tfoot>
                <tr>
                    <td colspan="6" class="footable-visible">

                    </td>
                </tr>
                </tfoot>


                <div class="clearfix"></div>
            </div>
        </div>

    </div>
</div>

<script>

</script>





