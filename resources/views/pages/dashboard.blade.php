@extends('index') 

@section('content')
     <div class="row mt-5">
     
            <div class="col-lg-3 col-6">
               <!-- small box -->
               <div class="small-box bg-info">
                  <div class="inner">
                     <!-- <h3></h3> -->
                     <p>Category</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-bag"></i>
                  </div>
                  <a href="{{route('show.categories')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
               </div>
            </div>
                     <!-- ./col -->
            <div class="col-lg-3 col-6">
               <!-- small box -->
               <div class="small-box bg-success">
                  <div class="inner">
                     <!-- <h3>53<sup style="font-size: 20px">%</sup></h3> -->
                     <p>Product</p>
                  </div>
                  <div class="icon">
                     <i class="ion ion-stats-bars"></i>
                  </div>
                  <a href="{{route('show.products')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
               </div>
            </div>
                     <!-- ./col -->
                     <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                           <div class="inner">
                              <!-- <h3>44</h3> -->
                              <p>Supplier</p>
                           </div>
                           <div class="icon">
                              <i class="ion ion-person-add"></i>
                           </div>
                           <a href="{{route('show.suppliers')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                     </div>
                     
                     <!-- <div class="col-lg-3 col-6">
                        
                        <div class="small-box bg-danger">
                           <div class="inner">
                             
                              <p>Customer</p>
                           </div>
                           <div class="icon">
                              <i class="ion ion-pie-graph"></i>
                           </div>
                           <a href="{{route('show.customers')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                     </div> -->
                     <div class="col-lg-3 col-6">
                        
                        <div class="small-box bg-danger">
                           <div class="inner">
                             
                              <p>Sales</p>
                           </div>
                           <div class="icon">
                              <i class="ion ion-pie-graph"></i>
                           </div>
                           <a href="{{route('show.transaction')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                     </div>
                     <div class="col-lg-3 col-6">
                        
                        <div class="small-box bg-success">
                           <div class="inner">
                              <p>Customer</p>
                           </div>
                           <div class="icon">
                              <i class="ion ion-pie-graph"></i>
                           </div>
                           <a href="{{route('show.customers')}}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                     </div>
                    
                    
    </div>
    @stop