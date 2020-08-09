<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>E-Council</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- <script src="https://kit.fontawesome.com/a076d05399.js"></script> -->
    <style>
body{
    font-family: ubuntu;
}
.imgDivReg{
    background-image: url("{{ asset('img/frontend/course/02.svg') }}");
    background-repeat: no-repeat;
    background-size: 100% 100%;
    margin-left: -3.5%;
    
}

.formDiv{
    
    height: 100vh;
    margin-left: 3%;
}

.loginBtn{
    background-color: #52ADE1;
    height: 50px;
}

.facebookBtn{
    background-color: #289AEC;
    height: 50px;
}

.facebookBtn img{
    background-size: cover;
    margin-right: 10px;
}


.googleBtn img{
    background-size:cover;
    margin-right: 10px;
}

.googleBtn{
    background-color: #CF2D48;
    height: 50px;
}

@media(max-width : 991px){
    .imgDiv{
        display: none;
    } 
    .imgDivReg{
        display: none;
    } 
}
</style>
</head>
<body>
    <div class="container-fluid">
      <div class="row">
        <div  class="col-md-6 imgDivReg">
        </div>
        <div class="formDiv col-lg-6 text-md-left text-center">
        <div class="row col-lg-8"> 
          <div class="col-md-10 offset-md-1">
            <img class="img-fluid"  src="{{ asset('img/frontend/course/E-Council.png') }}">
          </div>
         
          <div class="col-md-10 offset-md-1">
          <form action="{{route('frontend.auth.register.post')}}"
                                  method="POST" enctype="multipart/form-data">
                                  @csrf
            <div class="row">
              <div class="form-group col-md-6">
                <label for="exampleInputEmail1">@lang('labels.backend.sign_up.first_name')</label>
                <input name="first_name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
              </div>
  
              <div class="form-group col-md-6">
                <label for="exampleInputEmail1">@lang('labels.backend.sign_up.last_name')</label>
                <input name="last_name" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
              </div>  
            </div>
          
            <div class="form-group">
              <label for="exampleInput">@lang('labels.backend.sign_up.user_name')</label>
              <input type="text" class="form-control" id="exampleInput">
            </div>

            <div class="form-group">
              <label for="exampleInput">@lang('labels.backend.sign_up.email')</label>
              <input name="email" type="email" class="form-control" id="exampleInput">
            </div>

            <div class="form-group">
              <label for="exampleInputPassword1">@lang('labels.backend.sign_up.password')</label>
              <input name="password" type="password" class="form-control" id="exampleInputPassword1">
            </div>

            <div class="form-group">
              <label for="exampleInputPassword1">@lang('labels.backend.sign_up.confirm_password')</label>
              <input name="password_confirmation" type="password" class="form-control" id="exampleInputPassword1">
            </div>

            <div class="row">
              <div class="col-md-12">
                <div class="form-group form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1">
                <label class="form-check-label" for="exampleCheck1"> @lang('labels.backend.sign_up.agreement')</label>
              </div>
              </div>
            </div>
            <button type="submit" class="btn loginBtn text-white col-12 mt-5">@lang('labels.backend.sign_up.sign_up')</button>
          </form>
            <div><a href="{{ route('login.index') }}" class="text-dark col-12 d-flex justify-content-center mt-5">@lang('labels.backend.sign_up.already_have_account')</a></div>
            <div class="mb-2"><a href="#" class="text-dark col-12 d-flex justify-content-center mt-3">@lang('labels.backend.sign_up.terms_of_use')</a></div>
        
        </div>  
        </div>
      </div>
    </div>
</body>
</html>    