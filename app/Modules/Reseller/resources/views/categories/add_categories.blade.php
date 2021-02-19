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
                                <li class="breadcrumb-item"><a href="{{ url('/categories') }}">Categories</a></li>
                                <li class="breadcrumb-item active"><span>Add Category</span></li>
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
                            <h6 class="h6">Add Category</h6>
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
                                            <button type="button"  class="close " data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                          </button>
                                        </p>
                                        
                                    </div>
                                @endif
                                
  
                                <form id="add-category-form" action="{{ url('/categories') }}" method="post" >
                                    {{ csrf_field() }} 
                                    <!-- <div id="hidden_field"> </div> -->
                                    <div id="multipleAppend"></div>

                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Parent Category:</span>
                                        <div class="col-md-9">
                                            <select name="parent_id" id="parent_id" class="form-control">
                                                <option value="0">Please Select Parent Category</option>
                                                @foreach($parentcat as $cat)
                                                   <option value="{{$cat->id}}">{{$cat->category_name}}</option>
                                                @endforeach        
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Category Name*: </span>
                                        <div class="col-md-9">
                                            <input type="text"  name="categoryName" class="form-control">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Status*:</span>
                                        <div class="col-md-9">
                                            <select name="status" id="status" class="form-control">
                                                <option value="">Please Select Status</option>
                                                   <option value="1">Enable</option>     
                                                   <option value="0">Disable</option>
                                                  
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Custom fields</span>
                                        <div class="col-md-9">
                                              <button title="Add Custom field" type="button" style="font-size: 25px;" class="btn btn-rounded btn-success" id="add-new-custom-field">+</button>
                                        </div>

                                    </div>

                                      <div class="panel-group" id="accordion">
                                           
                                       </div> 
                                  
                                    

                                    <div class="row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="submit" value="Save" class="btn btn-rounded btn-success">
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