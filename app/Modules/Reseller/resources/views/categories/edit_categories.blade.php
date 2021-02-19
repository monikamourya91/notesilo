@extends('Admin::layouts.app')

@section('content')
 <!-- Wrapper Start -->
    <div class="wrapper">
       <!-- Navbar Start -->
       @include('Admin::layouts.navbar')
        <!-- Navbar End -->

         <!-- Sidebar sart -->
          @include('Admin::layouts.sidebar')
        <!-- Sidebar End -->

    <!-- Main Container Start -->
        <main class="main--container">
            <!-- Page Header Start -->
            <section class="page--header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Page Title Start -->
                            <h2 class="page--title h5">CATEGORIES</h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('admin/categories') }}">Categories</a></li>
                                <li class="breadcrumb-item active"><span>Edit Category</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Page Header End -->

            <!-- Main Content Start -->
            <section class="main--content">
                <div class="panel">

                    <!-- Edit Product Start -->
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">Edit Category</h6>
                        </div>

                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        <p>{{ $message }}
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
                                
  
                                <form id="edit-category-form" action="{{ url('/categories/'.$category->id) }}" method="post">
                                    {{ csrf_field() }}
                                    
                                    {{Method_field('PUT')}}

                                    <div id="multipleAppend"></div>

                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Parent Category:</span>
                                        <div class="col-md-9">
                                            <select name="parent_id" id="parent_id" class="form-control">
                                               
                                              
                                                <option value="0">Please Select Parent Category</option>
                                            
                                  

                                                @foreach($parentcat as $cat)
                                                   <option value="{{$cat->id}}" {{ $category->parent_id == $cat->id ?'selected':''}}
                                                    >{{$cat->category_name}}</option>
                                                 @endforeach    
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Category Name*: </span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{$category->category_name}}"  name="categoryName" class="form-control">
                                        </div>
                                    </div>
                                    @php
                                    $status=array('1'=>'Enable','0'=>'Disable')
                                    @endphp
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Status*:</span>
                                        <div class="col-md-9">
                                            <select name="status" class="form-control" required>
                                                <option value="">Please Select Status</option>
                                                  @foreach($status as $s => $s_value)
                                                  <option value="{{$s}}"@if($category->status==$s){{'selected'}}@endif>{{$s_value}}</option>
                                                 @endforeach 
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Custom fields</span>
                                        <div class="col-md-9">
                                              <button id="add-new-custom-field" title="Add Custom field" type="button" style="font-size: 25px;" class="btn btn-rounded btn-success editCustomAddBtn" >+</button>
                                        </div>


                                   
                                    </div>

                                   


@php
$cutomTypes = [ 
                    ["label"=>"Text", "value"=>'text'],
                    ["label"=>"Checkbox", "value"=>'checkbox'],
                    ["label"=>"Radio", "value"=>'radio'],
                    ["label"=>"Drop Down", "value"=>'select']
                ];
@endphp
   <div class="panel-group" id="accordion">
@if($field)
    @foreach($field as $key=>$f)
       <div class="panel panel-default custom-field-row" >
          <div class="panel-heading">
             <h4 class="panel-title w-100">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapse{{$key}}">Label : {{$f['validations']->label}}</a>
                <a edit-custom-field-id="{{$f['id']}}" class="remove-custom-field-row">Remove</a>   
             </h4>
          </div>
          <div id="collapse{{$key}}" class="panel-collapse collapse">
             <div class="panel-body">
                <div class="form-group row">
                   <span class="label-text col-md-3 col-form-label">Field Type*:</span>
                   <div class="col-md-9">
                      <select name="field_type" class="form-control field_type valid" aria-invalid="false">
                        @foreach ($cutomTypes as $custom) 
                            @if ($custom['value'] == $f['validations']->type) 
                                <option selected value="{{$f['validations']->type}}">{{$custom['label']}}</option>
                         
                            @else
                             <option  value="{{$custom['value']}}">{{$custom['label']}}</option>
                            @endif
                        @endforeach
                      </select>
                   </div>
                </div>
                <div class="dynamic-type-container">
                   <div class="checkboxTypeform type-connector" name="checkboxTypeform">
                      <input type="hidden" class="custom-field-id" value="{{$f['id']}}">
                      <div class="form-group row">
                         <span class="label-text col-md-3 col-form-label">Field Label*: </span>
                         <div class="col-md-9">
                            <input type="text" required value="{{$f['validations']->label}}" name="label" class="form-control custom-label-text">
                         </div>
                      </div>
                      @if($f['validations']->type == $cutomTypes[0])
                      <div class="form-group row">
                         <span class="label-text col-md-3 col-form-label">Default Value: </span>
                         <div class="col-md-9">
                            <input type="text" id="default" value="{{$f['validations']->placeholder}}" name="default" class="form-control placeholder">
                         </div>
                      </div>
                      @endif  
                      <div class="form-group row">
                         <span class="label-text col-md-3 col-form-label">Required: </span>
                         <div class="col-md-9">

                            <?php $isChecked =  $f['validations']->required == 'Yes' ? true : false;
                          
                            ?>

                            @if($isChecked)
                               <input type="checkbox" checked class="isrequired" name="required" data-toggle="toggle">
                            @else 
                              <input type="checkbox"  class="isrequired" name="required" data-toggle="toggle">


                            @endif
                         
                         </div>
                      </div>
                       @if($f['validations']->type == $cutomTypes[1] or $f['validations']->type == $cutomTypes[2] or $f['validations']->type == $cutomTypes[3])
                      <div class="form-group row">
                         <span class="label-text col-md-3 col-form-label">Choices*: </span>
                         <div class="col-md-9">
                          @php $choiceString = '';
                             foreach($f['validations']->choice as $ch) 
                               $choiceString .= $ch."\n";
                           @endphp
                             <textarea required="true" name="choice" rows="3" class="form-control custom-label-text choice" cols="50">{{$choiceString}}</textarea>
                         </div>
                      </div>
                      @endif

                        @if($f['validations']->type == $cutomTypes[3])

                      <div class="form-group row">
                            <span class="label-text col-md-3 col-form-label">Multiple select: </span>
                            <div class="col-md-9">

                            <?php $isChecked =  $f['validations']->multiple == 'Yes' ? true : false; ?>

                            @if($isChecked)
                                <input type="checkbox" checked name="multipleSelect" data-toggle="toggle">
                            @else 
                             <input type="checkbox"  name="multipleSelect" data-toggle="toggle">
                             @endif

                            </div>
                      </div>
                          @endif

                   </div>
                </div>
             </div>
          </div>
       </div>
    @endforeach 
@endif
 </div>  

                                    <div class="row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="submit" value="Update" class="btn btn-rounded btn-success">
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <!-- Tab Pane End -->
                        </div>
                        <!-- Tab Content End -->
                    </div>
                    <!-- Edit Product End -->
                </div>
            </section>
         
            <!-- Main Content End -->
    <!-- footer -->
       @include('Admin::layouts.main_footer')
     <!-- end footer -->
      <!-- Scripts -->
       @include('Admin::layouts.footer')
     <!-- Scripts -->
@endsection
