@extends('Reseller::layouts.app')

@section('content')
 <!-- Wrapper Start -->
    <div class="wrapper">
       <!-- Navbar Start -->
       @include('Reseller::layouts.navbar')
        <!-- Navbar End -->

         <!-- Sidebar sart -->
          @include('Reseller::layouts.sidebar')
        <!-- Sidebar End -->
    <!-- Main Container Start -->
        <main class="main--container">
            <!-- Main Content Start -->
            <section class="main--content">
                <div class="panel">
                    <!-- Records Header Start -->
                    <div class="records--header">
                        
                        <div class="title">
                            <a href="javascript:void(0);" class="page_title"> Payment Modes</a> 
                            <a href="{{route('reseller.AddNewPaymetMode')}}" class="btn btn-secondary cstm_add_btn"><i class="fa fa-plus-circle"></i> Add New</a>  
                        </div>
                    </div>
                    <!-- Records Header End -->
                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{!! $message !!}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </p>
                        
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        <p>{{ $message }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </p>
                        
                    </div>
                @endif

                <div class="panel">
                    <!-- Records List Start -->
                    @if($payment_modes->isEmpty())
                        <p class="alert alert-danger">You have not added any payment mode. <a href="{{route('reseller.AddNewPaymetMode')}}" class="clickherelink">Click here</a> to add your first payment mode.</p>
                    @else
                    <div class="records--list" data-title="Payment Modes Listing">
                        <table id="recordsListView">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Payment Mode</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th class="not-sortable">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;  
                                ?>  
                              @foreach($payment_modes as $mode)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        {{ucfirst($mode->payment_type)}}
                                    </td>
                                    <td>{{$mode->created_at}}</td>
                                    @php
                                    $status=array(
                                        '0'=>'Inactive',
                                        '1'=>'Active'
                                        );
                                    @endphp
                                    <td>
                                        @foreach($status as $s => $s_value)
                                           @if($mode->status==$s)
                                                <a href="{{route('reseller.EditPaymentMode',['id'=>$mode->id])}}" class="btn-link">
                                                 @if($s==0)
                                                        <span class="label label-danger">{{$s_value}}</span></a>
                                                    @elseif($s==1)
                                                        <span class="label label-success">{{$s_value}}</span></a>
                                                    @endif
                                                    
                                            @endif
                                        @endforeach 
                                    </td>
                                    
                                    <td>
                                        <div class="dropleft">
                                            <a href="#" class="btn-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                            @php
                                                $reseller_webhook = url('api/reseller-paddle-notification/'.$reseller_hash);
                                            @endphp
                                            <div class="dropdown-menu">
                                                <a href="javascript:void(0)" onclick="PaymentModeModalInfo('{{$mode->payment_type}}','{{$mode->credentials}}','{{$reseller_webhook}}')" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">View</button></a>
                                                <a href="{{route('reseller.EditPaymentMode',['id'=>$mode->id])}}" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">Edit</button></a>
                                                @if(!in_array($mode->id,$GatewaysAlreadyInUse))
                                                <a href="javascript:void(0)" onclick="deleteConfirmation('{{route('reseller.DeletePaymentMode',['id'=>$mode->id])}}','payment_mode')"; class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">Delete</button></a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                              @endforeach
                                
                            </tbody>
                        </table>
                    </div>
                    <!-- Records List End -->
                    @endif
                </div>


                <!-- Modal -->
                <div id="PaymentModeViewModal" class="modal fade" role="dialog">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Payment Mode Details</h4>
                      </div>
                      <div class="modal-body">
                            <div class="row">
                                <div class="col-md-4"><label>Payment Mode: </label></div>
                                <div class="col-md-8" id="paymentMode"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label>Vendor Id: </label></div>
                                <div class="col-md-8" id="vendorId"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label>API Key: </label></div>
                                <div class="col-md-8" id="apiKey"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><label>Webhook URL: </label></div>
                                <div class="col-md-8" id="webhook_url"></div>
                            </div>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

            </section>
            <!-- Main Content End -->
    <!-- footer -->
       @include('Reseller::layouts.main_footer')
     <!-- end footer -->
      <!-- Scripts -->
       @include('Reseller::layouts.footer')
     <!-- Scripts -->
     <script>
        function PaymentModeModalInfo(mode,credentials,reseller_hash){
            credentials = JSON.parse(credentials);
            $("#paymentMode").text(mode);
            $("#vendorId").text(credentials.vendor_id);
            $("#apiKey").text(credentials.api_key);
            $("#webhook_url").text(reseller_hash);
            $("#PaymentModeViewModal").modal('show');
        }
     </script>
@endsection
