<?php

use web\libs\Session;
?>
<nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur" navbar-scroll="true">
          <div class="container-fluid py-1 px-3">
              <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                  <ul class="me-auto"></ul>
                  <ul class="navbar-nav   justify-content-end">
                      <li class="nav-item d-flex align-items-center">
                          <form method="POST" action="<?=app_url('logout')?>" class="nav-link text-body font-weight-bold px-0" >
                             <button type="submit" class="btn btn-dark btn-sm ">
                                  <i class="fa fa-user me-sm-1"></i>
                                  <span class="d-sm-inline d-none">Sign out</span>
                             </button> 
                         </form>
                      </li>
                      <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                          <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                              <div class="sidenav-toggler-inner">
                                  <i class="sidenav-toggler-line"></i>
                                  <i class="sidenav-toggler-line"></i>
                                  <i class="sidenav-toggler-line"></i>
                              </div>
                          </a>
                      </li>
                      <li class="nav-item px-3 d-flex align-items-center">
                          <a href="javascript:;" class="nav-link text-body p-0">
                              <i class="fa fa-cog fixed-plugin-button-nav cursor-pointer"></i>
                          </a>
                      </li>

                  </ul>
              </div>
          </div>
      </nav>