@extends('layouts.master')

@section('auth-content')
<div class="col-lg-7 d-flex justify-content-center align-items-center">
    <div class="card shadow-sm w-100 p-4 p-md-5" style="max-width: 32rem;">
      <!-- Form -->
      <form class="row g-3">
         <div class="col-12 text-center mb-5">
               <h1>Sign in</h1>
               <span class="text-muted">Free access to our dashboard.</span>
         </div>
         <div class="col-12">
               <div class="mb-2">
                  <label class="form-label">Email address</label>
                  <input type="email" class="form-control form-control-lg" placeholder="name@example.com">
               </div>
         </div>
         <div class="col-12">
               <div class="mb-2">
                  <div class="form-label">
                     <span class="d-flex justify-content-between align-items-center">
                           Password
                           <a class="text-primary" href="auth-password-reset.html">Forgot Password?</a>
                     </span>
                  </div>
                  <input type="password" class="form-control form-control-lg" placeholder="***************">
               </div>
         </div>
         <div class="col-12">
               <div class="form-check">
                  <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
                  <label class="form-check-label" for="flexCheckDefault">
                     Remember me
                  </label>
               </div>
         </div>
         <div class="col-12 text-center mt-4">
               <a class="btn btn-lg btn-block btn-dark lift text-uppercase" href="index.html" title="">SIGN IN</a>
         </div>
      </form>
      <!-- End Form -->
    </div>
 </div>
@endsection
