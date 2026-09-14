@extends('layout.app')

@section('content')

<div class="sub-nav">
		<nav class="container">
  <div class="nav nav-tabs cus-nav-fxd" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-overview-tab" data-toggle="tab" data-bs-toggle="tab" href="#nav-overview" role="tab" aria-controls="nav-overview" aria-selected="true">Support</a>   
  </div>
</nav>
</div>
<div class="container tab-content" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-overview" role="tabpanel" aria-labelledby="nav-overview-tab">
      	<div class="card-box">
      		<div class="row">
      			<div class="col-md-8">
      				<form method="POST" action="{{route('supportstore')}}">
                @csrf
                <div class="form-group">
                  <label for="title">Title</label>
                  <input type="text" class="form-control" id="title" aria-describedby="emailHelp" name="title" placeholder="" required="">
                  
                </div>
                <div class="form-group">
                  <label for="exampleFormControlTextarea1">Description</label>
                  <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" name="description" required=""></textarea>
                </div>
               
                <button type="submit" class="btn btn-theme-dark">Submit</button>
              </form>
      			</div>
      		</div>
      		
      </div>
  </div>
</div>


@endsection